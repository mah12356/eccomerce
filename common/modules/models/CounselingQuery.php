<?php

namespace common\modules\models;

/**
 * This is the ActiveQuery class for [[Counseling]].
 *
 * @see Counseling
 */
class CounselingQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Counseling[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Counseling|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
