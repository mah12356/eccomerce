<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "regimes".
 *
 * @property int $id
 * @property string $type
 * @property int $calorie
 * @property string $fit
 * @property string $file
 * @property string $image
 * @property string $status
 */
class Regimes extends \yii\db\ActiveRecord
{
    const TYPE_DIET = 'diet'; // رژیم غذایی
    const TYPE_SHOCK = 'shock'; // رژیم شوک

    const FIT_NONE = 'none';
    const FIT_HEART = 'heart';

    const STATUS_ACTIVE = 'active';
    const STATUS_DELETED = 'delete';

    const UPLOAD_PATH = 'regimes';

    public $uploadFile;
    public $uploadImage;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'regimes';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'calorie', 'file'], 'required'],
            [['calorie'], 'integer'],
            [['type', 'fit', 'file', 'status'], 'string', 'max' => 255],
            [['uploadFile'], 'file', 'extensions' => 'pdf, jpg, png'],
            [['uploadImage'], 'file', 'extensions' => 'png, jpg, jpeg'],
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
            'calorie' => Yii::t('app', 'کالری'),
            'fit' => Yii::t('app', 'مناسب برای'),
            'file' => Yii::t('app', 'File'),
            'status' => Yii::t('app', 'Status'),
            'uploadFile' => Yii::t('app', 'بازگذاری فایل'),
            'uploadImage' => Yii::t('app', 'بارگذاری عکس'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegimesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegimesQuery(get_called_class());
    }

    public static function getType(): array
    {
        return [
            self::TYPE_DIET => 'رژیم غذایی',
            self::TYPE_SHOCK => 'رژیم شوک',
        ];
    }

    public static function getFit(): array
    {
        return [
            self::FIT_NONE => 'هیچکدام',
            self::FIT_HEART => 'قلب و عروق',
        ];
    }
}
