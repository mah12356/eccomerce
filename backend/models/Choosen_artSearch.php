<?php

namespace backend\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\models\ChoosenArt;

/**
 * Choosen_artSearch represents the model behind the search form of `backend\models\ChoosenArt`.
 */
class Choosen_artSearch extends ChoosenArt
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'art_id', 'date'], 'integer'],
            [['banner', 'title', 'status'], 'safe'],
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
        $query = ChoosenArt::find();

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
            'art_id' => $this->art_id,
            'date' => $this->date,
            'category_id'=>$this->category_id
        ]);

        $query->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
