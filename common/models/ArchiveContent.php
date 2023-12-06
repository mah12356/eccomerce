<?php

namespace common\models;

use common\components\Gadget;
use Yii;

/**
 * This is the model class for table "archive_content".
 *
 * @property int $id
 * @property int $archive_id
 * @property int $sort
 * @property string $title
 * @property string $file
 * @property string|null $text
 */
class ArchiveContent extends \yii\db\ActiveRecord
{
    const UPLOAD_FILE = 'archives';

    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archive_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['archive_id', 'sort', 'title', 'file'], 'required'],
            [['archive_id', 'sort'], 'integer'],
            [['text'], 'string'],
            [['title', 'file'], 'string', 'max' => 255],
            [['uploadFile'], 'file', 'extensions' => 'mp4, png, jpg, jpeg'],
            ['sort', 'validateSort'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'archive_id' => Yii::t('app', 'Archive ID'),
            'sort' => Yii::t('app', 'ترتیب'),
            'title' => Yii::t('app', 'عنوان'),
            'file' => Yii::t('app', 'File'),
            'text' => Yii::t('app', 'متن'),
            'uploadFile' => Yii::t('app', 'بارگذاری فایل'),
        ];
    }

    public function validateSort(string $attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->sort = (int)Gadget::convertToEnglish($this->sort);
            if ($this->sort == 0) {
                $this->addError($attribute, 'ترتیب نمایش باید یک عدد باشد');
            }
            $model = ArchiveContent::findOne(['archive_id' => $this->archive_id, 'sort' => $this->sort]);
            if ($model) {
                $this->addError($attribute, 'ترتیب نمایش تکراری است');
            }
        }
    }

    /**
     * {@inheritdoc}
     * @return ArchiveContentQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArchiveContentQuery(get_called_class());
    }
}
