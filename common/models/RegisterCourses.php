<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "register_courses".
 *
 * @property int $id
 * @property int $register_id
 * @property int $course_id
 */
class RegisterCourses extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'register_courses';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['register_id', 'course_id'], 'required'],
            [['register_id', 'course_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'register_id' => Yii::t('app', 'Register ID'),
            'course_id' => Yii::t('app', 'Course ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegisterCoursesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegisterCoursesQuery(get_called_class());
    }

    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }
}
