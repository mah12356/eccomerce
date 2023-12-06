<?php

namespace common\models;

use common\components\Gadget;
use Yii;

/**
 * This is the model class for table "course_sections".
 *
 * @property int $id
 * @property int $course_id
 * @property int $section_group
 */
class CourseSections extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course_sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['course_id', 'section_group'], 'required'],
            [['course_id', 'section_group'], 'integer'],
            ['section_group', 'validateGroup'],
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
            'section_group' => Yii::t('app', 'گروه جلسات'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CourseSectionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourseSectionsQuery(get_called_class());
    }

    public function validateGroup($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $model = CourseSections::findOne(['section_group' => $this->section_group, 'course_id' => $this->course_id]);
            if ($model) {
                $this->addError($attribute, 'گروه جلسات انتخابی تکراری است');
            }
        }
    }

    public function getSections()
    {
        return $this->hasOne(Sections::className(), ['group' => 'section_group'])->groupBy(['group']);
    }
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'section_group']);
    }
    public function getHeld()
    {
        return $this->hasMany(Sections::className(), ['group' => 'section_group'])->andWhere(['status' => Sections::STATUS_COMPLETE, 'mood' => Sections::MOOD_COMPLETE])->orderBy(['date' => SORT_DESC]);
    }

    public function getAll()
    {
        return $this->hasMany(Sections::className(), ['group' => 'section_group']);
    }
}
