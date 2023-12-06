<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Archives]].
 *
 * @see Archives
 */
class ArchivesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Archives[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Archives|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
