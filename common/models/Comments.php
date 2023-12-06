<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "comments".
 *
 * @property int $id
 * @property string $name
 * @property string|null $period
 * @property string $text
 * @property string $avatar
 */
class Comments extends \yii\db\ActiveRecord
{
    const UPLOAD_PATH = 'img';

    public $avatarFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'comments';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'text'], 'required'],
            [['name', 'period', 'text', 'avatar'], 'string', 'max' => 255],
            [['avatarFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'نام و نام‌خانوادگی'),
            'period' => Yii::t('app', 'مدت زمان تمرین'),
            'text' => Yii::t('app', 'نوشته'),
            'avatar' => Yii::t('app', 'Avatar'),
            'avatarFile' => Yii::t('app', 'بارگذاری عکس'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CommentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommentsQuery(get_called_class());
    }
}
