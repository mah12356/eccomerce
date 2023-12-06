<?php

namespace common\modules\blog\models;

use Yii;

/**
 * This is the model class for table "gallery".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $belong
 * @property string $type
 * @property string $image
 * @property string|null $alt
 */
class Gallery extends \yii\db\ActiveRecord
{
    const BELONG_HOME = 'home';
    const BELONG_POSTS = 'post';

    const TYPE_CARD = 'card';
    const TYPE_BANNER = 'banner';
    const TYPE_VIDEO = 'video';

    const TYPE_COMPARE = 'compare';

    const UPLOAD_PATH = 'gallery';

    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'gallery';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'belong', 'type', 'image'], 'required'],
            [['parent_id'], 'integer'],
            [['belong', 'type', 'image', 'alt'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg, webp, mp4'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'belong' => Yii::t('app', 'متعلق به'),
            'type' => Yii::t('app', 'نوع'),
            'file' => Yii::t('app', 'بارگذاری فایل'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
        ];
    }

    public static function getType()
    {
        return [
            self::TYPE_CARD => 'کارت',
//            self::TYPE_BANNER => 'بنر',
//            self::TYPE_VIDEO => 'ویدیو',
        ];
    }

    /**
     * {@inheritdoc}
     * @return GalleryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }
}
