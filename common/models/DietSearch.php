<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Diet;
use yii\helpers\ArrayHelper;

/**
 * DietSearch represents the model behind the search form of `common\models\Diet`.
 */
class DietSearch extends Diet
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'package_id', 'course_id', 'regime_id', 'date', 'date_update'], 'integer'],
            [['name', 'lastname', 'mobile', 'status'], 'safe'],
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
        $activePackages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $activeRegister = Register::find()->where(['IN', 'package_id', ArrayHelper::map($activePackages, 'id', 'id')])
            ->andWhere(['payment' => Register::PAYMENT_ACCEPT])->asArray()->all();

        $query = Diet::find()->where(['IN', 'diet.status', [Diet::STATUS_WAIT_FOR_ANSWERS, Diet::STATUS_WAIT_FOR_RESPONSE, Diet::STATUS_REGIME_NOT_FOUND]])
            ->andWhere(['IN', 'register_id', ArrayHelper::map($activeRegister, 'id', 'id')])->with('user')->with('package');

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

        $query->joinWith('user');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'package_id' => $this->package_id,
            'course_id' => $this->course_id,
            'regime_id' => $this->regime_id,
            'date' => $this->date,
            'date_update' => $this->date_update,
        ]);

        $query->andFilterWhere(['like', 'diet.status', $this->status])
            ->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.lastname', $this->lastname])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile]);

        return $dataProvider;
    }
}
