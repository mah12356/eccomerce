<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "faq".
 *
 * @property int $id
 * @property string $belong
 * @property int $sort
 * @property string $question
 * @property string $answer
 * @property string $file
 */
class Faq extends \yii\db\ActiveRecord
{
    const BELONG_HOME = 'home';

    const UPLOAD_PATH = 'faq';

    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'faq';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['belong', 'sort', 'question', 'answer'], 'required'],
            [['sort'], 'integer'],
            [['answer'], 'string'],
            [['belong', 'question', 'file'], 'string', 'max' => 255],
            [['uploadFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp, mp4, mp3, m4a'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'belong' => Yii::t('app', 'Belong'),
            'sort' => Yii::t('app', 'ترتیب'),
            'question' => Yii::t('app', 'سوال'),
            'answer' => Yii::t('app', 'پاسخ'),
            'file' => Yii::t('app', 'File'),
            'uploadFile' => Yii::t('app', 'بارگذاری فایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FaqQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FaqQuery(get_called_class());
    }
}
