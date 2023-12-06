<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Analyze]].
 *
 * @see Analyze
 */
class AnalyzeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Analyze[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Analyze|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
