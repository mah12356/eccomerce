<?php

namespace common\modules\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\blog\models\Posts;

/**
 * PostsSearch represents the model behind the search form of `common\modules\blog\models\Posts`.
 */
class PostsSearch extends Posts
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'page_id', 'order'], 'integer'],
            [['belong', 'title', 'text', 'tip', 'banner', 'alt'], 'safe'],
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
        $query = Posts::find()->orderBy(['order' => SORT_DESC])->addOrderBy(['id' => SORT_DESC]);

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
            'page_id' => $this->page_id,
            'order' => $this->order,
        ]);

        $query->andFilterWhere(['like', 'belong', $this->belong])
            ->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'text', $this->text])
            ->andFilterWhere(['like', 'tip', $this->tip])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'alt', $this->alt]);

        return $dataProvider;
    }
}
