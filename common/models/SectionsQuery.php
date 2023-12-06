<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Sections]].
 *
 * @see Sections
 */
class SectionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Sections[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Sections|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
