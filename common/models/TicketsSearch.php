<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Tickets;

/**
 * TicketsSearch represents the model behind the search form of `common\models\Tickets`.
 */
class TicketsSearch extends Tickets
{
    public $_type;
    public $_status;

    public function __construct($type = null, $status = null,$config = [])
    {
        $this->_type = ($type != null) ? $type : $this->type;
        $this->_status = ($status != null) ? $status : $this->status;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['name', 'lastname', 'mobile', 'type', 'title', 'status'], 'safe'],
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
        $query = Tickets::find()->with('user');

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
            'type' => $this->_type,
            'tickets.status' => $this->_status,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'user.name', $this->name])
            ->andFilterWhere(['like', 'user.lastname', $this->lastname])
            ->andFilterWhere(['like', 'user.mobile', $this->mobile])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'tickets.status', $this->status]);

        return $dataProvider;
    }
}
