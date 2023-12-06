<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Coach]].
 *
 * @see Coach
 */
class CoachQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Coach[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Coach|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
