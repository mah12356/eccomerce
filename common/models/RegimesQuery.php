<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Regimes]].
 *
 * @see Regimes
 */
class RegimesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Regimes[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Regimes|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
