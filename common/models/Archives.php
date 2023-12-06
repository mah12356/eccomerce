<?php

namespace common\models;

use common\modules\main\models\Category;
use Yii;

/**
 * This is the model class for table "archives".
 *
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $subject
 * @property string $intro
 * @property string|null $description
 * @property string|null $btn
 * @property string|null $link
 * @property string $poster
 * @property string|null $alt
 * @property int $status
 */
class Archives extends \yii\db\ActiveRecord
{
    const UPLOAD_PATH = 'archives';

    public $imageFile;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'archives';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'title', 'subject', 'intro'], 'required'],
            [['category_id', 'status'], 'integer'],
            [['description'], 'string'],
            [['title', 'subject', 'intro', 'btn', 'link', 'poster', 'alt'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'category_id' => Yii::t('app', 'دسته آرشیو'),
            'title' => Yii::t('app', 'عنوان صفحه'),
            'subject' => Yii::t('app', 'عنوان'),
            'intro' => Yii::t('app', 'توضیح اجمالی'),
            'description' => Yii::t('app', 'توضیحات'),
            'btn' => Yii::t('app', 'نوشته دکمه'),
            'link' => Yii::t('app', 'لینک'),
            'poster' => Yii::t('app', 'پوستر'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
            'status' => Yii::t('app', 'Status'),
            'imageFile' => Yii::t('app', 'بارگذاری عکس پوستر'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ArchivesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArchivesQuery(get_called_class());
    }

    public function getCategory()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }

    public function getContent()
    {
        return $this->hasMany(ArchiveContent::className(), ['archive_id' => 'id'])->orderBy(['sort' => SORT_ASC]);
    }
}
