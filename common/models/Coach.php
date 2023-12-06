<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "coach".
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $instagram_id
 * @property string|null $bio
 * @property string|null $phone
 * @property string|null $description
 * @property string $avatar
 * @property string $poster
 * @property string $video
 */
class Coach extends ActiveRecord
{
    public $avatarFile;
    public $posterFile;
    public $videoFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'coach';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id'], 'integer'],
            [['description'], 'string'],
            [['instagram_id', 'bio', 'avatar', 'poster', 'video'], 'string', 'max' => 255],
            [['phone'], 'string', 'max' => 11],
            [['avatarFile', 'posterFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp'],
            [['videoFile'], 'file', 'extensions' => 'mp4'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'instagram_id' => Yii::t('app', 'آی دی اینستاگرام'),
            'bio' => Yii::t('app', 'بیوگرافی'),
            'phone' => Yii::t('app', 'شماره تلفن'),
            'description' => Yii::t('app', 'توضیحات'),
            'avatarFile' => Yii::t('app', 'بارگذاری پروفایل'),
            'posterFile' => Yii::t('app', 'بارگذاری پستر'),
            'videoFile' => Yii::t('app', 'بارگذاری ویدیو'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CoachQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CoachQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCourse()
    {
        return $this->hasMany(Course::className(), ['coach_id' => 'user_id']);
    }
}
