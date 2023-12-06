<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[RegisterCourses]].
 *
 * @see RegisterCourses
 */
class RegisterCoursesQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return RegisterCourses[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return RegisterCourses|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
