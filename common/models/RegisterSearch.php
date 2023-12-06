<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Register;

/**
 * RegisterSearch represents the model behind the search form of `common\models\Register`.
 */
class RegisterSearch extends Register
{
    public $_package;
    public $_payment;

    public function __construct($package_id = null, $payment = null, $config = [])
    {
        $this->_package = ($package_id != null) ? $package_id : $this->package_id;
        $this->_payment = ($payment != null) ? $payment : $this->payment;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'package_id', 'factor_id'], 'integer'],
            [['payment', 'status', 'name', 'lastname', 'mobile'], 'safe'],
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
        $query = Register::find()->with('user');

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
            'package_id' => $this->_package,
            'factor_id' => $this->factor_id,
            'payment' => $this->_payment,
        ]);

        $query->andFilterWhere(['like', 'payment', $this->payment])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.lastname', $this->lastname])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile]);

        return $dataProvider;
    }
}
