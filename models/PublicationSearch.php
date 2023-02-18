<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Publication;

/**
 * PublicationSearch represents the model behind the search form of `app\models\Publication`.
 */
class PublicationSearch extends Publication
{
    public $projectId;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'event_id', 'type', 'views', 'projectId'], 'integer'],
            [['date_in', 'link'], 'safe'],
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
        $query = Publication::find();

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

        if($this->projectId) {
            $query->innerJoinWith(['event e']);
            $query->andWhere(['e.project_id' => $this->projectId]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'event_id' => $this->event_id,
            'type' => $this->type,
            'date_in' => $this->date_in,
            'views' => $this->views,
        ]);

        $query->andFilterWhere(['ilike', 'link', $this->link]);

        return $dataProvider;
    }
}
