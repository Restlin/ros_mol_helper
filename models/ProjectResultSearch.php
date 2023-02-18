<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ProjectResult;

/**
 * ProjectResultSearch represents the model behind the search form of `app\models\ProjectResult`.
 */
class ProjectResultSearch extends ProjectResult
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'project_id', 'events', 'men', 'publications', 'views'], 'integer'],
            [['effect'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ProjectResult::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'project_id' => $this->project_id,
            'events' => $this->events,
            'men' => $this->men,
            'publications' => $this->publications,
            'views' => $this->views,
        ]);

        $query->andFilterWhere(['ilike', 'effect', $this->effect]);

        return $dataProvider;
    }
}
