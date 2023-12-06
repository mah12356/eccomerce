<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Factor]].
 *
 * @see Factor
 */
class FactorQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Factor[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Factor|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
