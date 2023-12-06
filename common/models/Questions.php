<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "questions".
 *
 * @property int $id
 * @property int $course_id
 * @property string $type
 * @property string $title
 * @property int $required
 */
class Questions extends \yii\db\ActiveRecord
{
    const TYPE_TEXT = 'text';
    const TYPE_NUMBER = 'number';
    const TYPE_DROPDOWN = 'dropdown';
    const TYPE_CHECKBOX = 'checkbox';
    const TYPE_RADIO = 'radio';

    const REQUIRED_TRUE = 1;
    const REQUIRED_FALSE = 0;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'questions';
    }

    public static function getType()
    {
        return [
            self::TYPE_TEXT => 'نوشته',
            self::TYPE_NUMBER => 'عددی',
            self::TYPE_DROPDOWN => 'کشویی',
            self::TYPE_CHECKBOX => 'انتخابی',
            self::TYPE_RADIO => 'گزینه ای',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'type', 'title', 'required'], 'required'],
            [['course_id', 'required'], 'integer'],
            [['type', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'type' => Yii::t('app', 'نوع سوال'),
            'title' => Yii::t('app', 'صورت سوال'),
            'required' => Yii::t('app', 'اجباری بودن'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return QuestionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new QuestionsQuery(get_called_class());
    }

    public function getOptions()
    {
        return $this->hasMany(Options::className(), ['question_id' => 'id']);
    }
}
