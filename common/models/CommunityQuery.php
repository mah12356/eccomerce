<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Community]].
 *
 * @see Community
 */
class CommunityQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Community[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Community|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
