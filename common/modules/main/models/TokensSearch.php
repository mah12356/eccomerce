<?php

namespace common\modules\main\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\modules\main\models\Tokens;

/**
 * TokensSearch represents the model behind the search form of `common\modules\main\models\Tokens`.
 */
class TokensSearch extends Tokens
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'code', 'sent_at'], 'integer'],
            [['mobile'], 'safe'],
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
        $query = Tokens::find();

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
            'code' => $this->code,
            'sent_at' => $this->sent_at,
        ]);

        $query->andFilterWhere(['like', 'mobile', $this->mobile]);

        return $dataProvider;
    }
}
