<?php

namespace common\modules\blog\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\blog\models\Articles;

/**
 * ArticlesSearch represents the model behind the search form of `common\modules\blog\models\Articles`.
 */
class ArticlesSearch extends Articles
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'modify_date', 'publish', 'preview'], 'integer'],
            [['category_id', 'page_title', 'title', 'introduction', 'poster', 'banner', 'alt'], 'safe'],
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
        $query = Articles::find()->orderBy(['id' => SORT_DESC]);

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
            'articles.id' => $this->id,
            'modify_date' => $this->modify_date,
            'publish' => $this->publish,
            'preview' => $this->preview,
        ]);

        $query->andFilterWhere(['like', 'articles.title', $this->title])
            ->andFilterWhere(['like', 'introduction', $this->introduction])
            ->andFilterWhere(['like', 'category.title', $this->category_id])
            ->andFilterWhere(['like', 'poster', $this->poster])
            ->andFilterWhere(['like', 'banner', $this->banner])
            ->andFilterWhere(['like', 'alt', $this->alt]);

        return $dataProvider;
    }
}
