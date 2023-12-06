<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Hints]].
 *
 * @see Hints
 */
class HintsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Hints[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Hints|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
