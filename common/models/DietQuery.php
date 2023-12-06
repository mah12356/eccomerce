<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Diet]].
 *
 * @see Diet
 */
class DietQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Diet[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Diet|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
