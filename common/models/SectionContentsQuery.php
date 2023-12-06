<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[SectionContents]].
 *
 * @see SectionContents
 */
class SectionContentsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return SectionContents[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return SectionContents|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
