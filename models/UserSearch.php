<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\User;

/**
 * UserSearch represents the model behind the search form of `app\models\User`.
 */
class UserSearch extends User
{
    public $withoutAdmin = false;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'tg_id', 'role'], 'integer'],
            [['email', 'fio', 'password_hash', 'photo', 'about', 'url'], 'safe'],
            [['withoutAdmin'], 'boolean'],
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
        $query = User::find();

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

        if($this->withoutAdmin) {
            $query->andWhere(['<>', 'role', self::ROLE_ADMIN]);
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'tg_id' => $this->tg_id,
            'role' => $this->role,
        ]);

        $query->andFilterWhere(['ilike', 'email', $this->email])
            ->andFilterWhere(['ilike', 'fio', $this->fio])
            ->andFilterWhere(['ilike', 'password_hash', $this->password_hash])
            ->andFilterWhere(['ilike', 'about', $this->about])
            ->andFilterWhere(['ilike', 'url', $this->url])
            ->andFilterWhere(['ilike', 'photo', $this->photo]);

        return $dataProvider;
    }
}
