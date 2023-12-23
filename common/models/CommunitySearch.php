<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Community;

/**
 * CommunitySearch represents the model behind the search form of `common\models\Community`.
 */
class CommunitySearch extends Community
{
    public $_parent;
    public $_belong;
    public $_status;

    public function __construct($parent_id = 0, $belong = null, $status = null, $config = [])
    {
        $this->_parent = ($parent_id != 0) ? $parent_id : $this->parent_id;
        $this->_belong = ($belong != null) ? $belong : $this->belong;
        $this->_status = ($belong != null) ? $status : $this->status;

        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'parent_id', 'date'], 'integer'],
            [['belong', 'text', 'reply', 'status'], 'safe'],
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
        $query = Community::find()->with('article');

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
            'user_id' => $this->user_id,
            'parent_id' => $this->_parent,
            'date' => $this->date,
            'belong' => $this->_belong,
            'status' => $this->_status,
        ]);

        $query->andFilterWhere(['like', 'belong', $this->belong])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'reply', $this->reply])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}