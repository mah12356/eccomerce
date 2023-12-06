<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "stu".
 *
 * @property int $id
 * @property string $name
 * @property string $lastname
 * @property string $mobile
 * @property string $course
 * @property string $options
 */
class Stu extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'stu';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'lastname', 'mobile', 'course', 'options'], 'required'],
            [['name', 'lastname', 'mobile', 'course', 'options'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'lastname' => Yii::t('app', 'Lastname'),
            'mobile' => Yii::t('app', 'Mobile'),
            'course' => Yii::t('app', 'Course'),
            'options' => Yii::t('app', 'Options'),
        ];
    }
}
