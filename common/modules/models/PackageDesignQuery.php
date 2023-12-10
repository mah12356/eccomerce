<?php

namespace common\modules\models;

/**
 * This is the ActiveQuery class for [[PackageDesign]].
 *
 * @see PackageDesign
 */
class PackageDesignQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return PackageDesign[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return PackageDesign|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
