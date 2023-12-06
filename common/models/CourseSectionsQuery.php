<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[CourseSections]].
 *
 * @see CourseSections
 */
class CourseSectionsQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return CourseSections[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return CourseSections|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
