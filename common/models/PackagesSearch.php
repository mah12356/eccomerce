<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Packages;

/**
 * PackagesSearch represents the model behind the search form of `common\models\Packages`.
 */
class PackagesSearch extends Packages
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'coach_id', 'discount', 'start_register', 'end_register', 'start_date', 'period'], 'integer'],
            [['category', 'name', 'description', 'alt', 'status'], 'safe'],
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
        $query = Packages::find()->where(['IN', 'status', [Packages::STATUS_READY, Packages::STATUS_PREPARE]])
            ->orderBy(['id' => SORT_DESC]);

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

        $query->joinWith('cat');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'coach_id' => $this->coach_id,
            'discount' => $this->discount,
            'start_register' => $this->start_register,
            'end_register' => $this->end_register,
            'start_date' => $this->start_date,
            'period' => $this->period,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'category.title', $this->category])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'poster', $this->poster])
            ->andFilterWhere(['like', 'alt', $this->alt])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
