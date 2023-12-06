<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Analyze;

/**
 * AnalyzeSearch represents the model behind the search form of `common\models\Analyze`.
 */
class AnalyzeSearch extends Analyze
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'register_id', 'package_id', 'course_id', 'date', 'updated_at'], 'integer'],
            [['name', 'lastname', 'mobile', 'height', 'weight', 'age', 'gender', 'wrist', 'arm', 'chest', 'under_chest', 'belly', 'waist', 'hip', 'thigh', 'shin', 'front_image', 'side_image', 'back_image', 'status'], 'safe'],
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
        $query = Analyze::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['package_id' => SORT_DESC])->addOrderBy(['updated_at' => SORT_DESC])
            ->with('package'),
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->joinWith('user');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'register_id' => $this->register_id,
            'package_id' => $this->package_id,
            'course_id' => $this->course_id,
            'date' => $this->date,
            'updated_at' => $this->updated_at,
            'analyze.status' => $this->status
        ]);

        $query->andFilterWhere(['like', 'height', $this->height])
            ->andFilterWhere(['like', 'weight', $this->weight])
            ->andFilterWhere(['like', 'age', $this->age])
            ->andFilterWhere(['like', 'gender', $this->gender])
            ->andFilterWhere(['like', 'wrist', $this->wrist])
            ->andFilterWhere(['like', 'arm', $this->arm])
            ->andFilterWhere(['like', 'chest', $this->chest])
            ->andFilterWhere(['like', 'under_chest', $this->under_chest])
            ->andFilterWhere(['like', 'belly', $this->belly])
            ->andFilterWhere(['like', 'waist', $this->waist])
            ->andFilterWhere(['like', 'hip', $this->hip])
            ->andFilterWhere(['like', 'thigh', $this->thigh])
            ->andFilterWhere(['like', 'shin', $this->shin])
            ->andFilterWhere(['like', 'front_image', $this->front_image])
            ->andFilterWhere(['like', 'side_image', $this->side_image])
            ->andFilterWhere(['like', 'back_image', $this->back_image])
            ->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.lastname', $this->lastname])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile]);

        return $dataProvider;
    }
}
