<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[ArchiveContent]].
 *
 * @see ArchiveContent
 */
class ArchiveContentQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return ArchiveContent[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return ArchiveContent|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
