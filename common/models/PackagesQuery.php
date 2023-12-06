<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Packages]].
 *
 * @see Packages
 */
class PackagesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Packages[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Packages|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
