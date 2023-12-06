<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "hints".
 *
 * @property int $id
 * @property string $type
 * @property string $belong
 * @property string $title
 * @property string $text
 * @property string $btn
 * @property string $link
 * @property int $date
 * @property string $typePackage
 */
class Hints extends \yii\db\ActiveRecord
{
    const TYPE_NOTIFICATION = 'notification';
    const TYPE_INSTANT = 'instant';


    const BELONG_HOME_PAGE = 'home page';
    const BELONG_DIET_TICKET = 'diet ticket';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'hints';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'belong', 'title', 'date'], 'required'],
            [['text'], 'string'],
            [['date'], 'integer'],
            [['type', 'belong', 'title', 'btn', 'link','typePackage'], 'string', 'max' => 255],
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
            'belong' => Yii::t('app', 'متعلق به'),
            'title' => Yii::t('app', 'عنوان'),
            'text' => Yii::t('app', 'متن'),
            'btn' => Yii::t('app', 'نوشته دکمه'),
            'link' => Yii::t('app', 'لینک'),
            'date' => Yii::t('app', 'Date'),
            'typePackage' => Yii::t('app', 'پکیج'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return HintsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HintsQuery(get_called_class());
    }

    public static function getType()
    {
        return [
            self::TYPE_INSTANT => 'فوری',
            self::TYPE_NOTIFICATION => 'اطلاعیه',
        ];
    }

    public static function getBelong()
    {
        return [
            self::BELONG_HOME_PAGE => 'صفحه اصلی',
            self::BELONG_DIET_TICKET => 'تیکت تغذیه',
        ];
    }
}
