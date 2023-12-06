<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "media".
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property string|null $caption
 * @property string|null $description
 * @property string $poster
 * @property string $file
 */
class Media extends \yii\db\ActiveRecord
{

    const TYPE_GUIDE = 'guide';

    const UPLOAD_PATH = 'media';

    public $posterFile;
    public $fileInput;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'media';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['description'], 'string'],
            [['type', 'title', 'caption', 'poster', 'file'], 'string', 'max' => 255],
            [['posterFile'], 'file', 'extensions' => 'jpg, jpeg, webp, png'],
            [['fileInput'], 'file', 'extensions' => 'mp4']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'نوع'),
            'title' => Yii::t('app', 'عنوان'),
            'caption' => Yii::t('app', 'کپشن'),
            'description' => Yii::t('app', 'توضیحات'),
            'poster' => Yii::t('app', 'پوستر'),
            'file' => Yii::t('app', 'File'),
            'posterFile' => Yii::t('app', 'بارگذاری پوستر'),
            'fileInput' => Yii::t('app', 'بارگذاری ویدیو'),
        ];
    }
}
