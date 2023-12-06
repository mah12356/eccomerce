<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Sections;

/**
 * SectionsSearch represents the model behind the search form of `common\models\Sections`.
 */
class SectionsSearch extends Sections
{
    public $_group;

    public function __construct($group = null, $config = [])
    {
        $this->_group = ($group != null) ? $group : $this->group;
        parent::__construct($config);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'group', 'date', 'start_at', 'end_at'], 'integer'],
            [['title', 'type', 'input_url', 'player_url', 'hls', 'status', 'mood'], 'safe'],
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
        $query = Sections::find()->orderBy(['date' => SORT_ASC]);

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
            'group' => $this->_group,
            'date' => $this->date,
            'start_at' => $this->start_at,
            'end_at' => $this->end_at,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'type', $this->type])
            ->andFilterWhere(['like', 'input_url', $this->input_url])
            ->andFilterWhere(['like', 'player_url', $this->player_url])
            ->andFilterWhere(['like', 'hls', $this->hls])
            ->andFilterWhere(['like', 'status', $this->status])
            ->andFilterWhere(['like', 'mood', $this->mood]);

        return $dataProvider;
    }
}
