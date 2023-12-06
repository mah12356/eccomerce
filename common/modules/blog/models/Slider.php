<?php

namespace common\modules\blog\models;

use Yii;

/**
 * This is the model class for table "slider".
 *
 * @property int $id
 * @property int $page_id
 * @property string $belong
 * @property string|null $title
 * @property string|null $text
 * @property string|null $btn
 * @property string|null $url
 * @property string $image
 * @property string|null $alt
 */
class Slider extends \yii\db\ActiveRecord
{
    const BELONG_HOME = 'home';


    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'slider';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'belong', 'image'], 'required'],
            [['page_id'], 'integer'],
            [['belong', 'title', 'text', 'btn', 'url', 'image', 'alt'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_id' => Yii::t('app', 'Page ID'),
            'belong' => Yii::t('app', 'Belong'),
            'title' => Yii::t('app', 'عنوان'),
            'text' => Yii::t('app', 'نوشته'),
            'btn' => Yii::t('app', 'نوشته دکمه'),
            'url' => Yii::t('app', 'آدرس صفحه'),
            'image' => Yii::t('app', 'Image'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SliderQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SliderQuery(get_called_class());
    }
}
