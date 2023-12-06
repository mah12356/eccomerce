<?php

namespace common\models;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "course".
 *
 * @property int $id
 * @property int $package_id
 * @property string $belong
 * @property string $name
 * @property int $price
 * @property int $count
 * @property int $required
 * @property string|null $description
 * @property string $poster
 * @property string|null $alt
 */
class Course extends ActiveRecord
{
    const BELONG_DIET = 'diet';
    const BELONG_SHOCK_DIET = 'shock';
    const BELONG_LIVE = 'live';
    const BELONG_OFFLINE = 'offline';
    const BELONG_ANALYZE = 'analyze';

    const REQUIRED_TRUE = 1;
    const REQUIRED_FALSE = 0;

    const UPLOAD_PATH = 'course';

    public $posterFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'course';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['package_id', 'belong', 'name', 'price'], 'required'],
            [['package_id', 'price', 'count', 'required'], 'integer'],
            [['description'], 'string'],
            [['belong', 'name', 'poster', 'alt'], 'string', 'max' => 255],
            [['posterFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp']
        ];
    }

    public static function getBelong()
    {
        return [
            self::BELONG_LIVE => 'پخش زنده',
            self::BELONG_OFFLINE => 'آفلاین',
            self::BELONG_DIET => 'برنامه غذایی',
            self::BELONG_SHOCK_DIET => 'رژیم شوک',
            self::BELONG_ANALYZE => 'آنالیز',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'package_id' => Yii::t('app', 'Package ID'),
            'belong' => Yii::t('app', 'نوع'),
            'name' => Yii::t('app', 'نام دوره'),
            'price' => Yii::t('app', 'قیمت'),
            'count' => Yii::t('app', 'تعداد برنامه‌های غذایی'),
            'required' => Yii::t('app', 'اجباری بودن'),
            'description' => Yii::t('app', 'توضیحات'),
            'poster' => Yii::t('app', 'پوستر'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
            'posterFile' => Yii::t('app', 'بارگذاری پوستر'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CourseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CourseQuery(get_called_class());
    }

    public function getPackage()
    {
        return $this->hasOne(Packages::className(), ['id' => 'package_id']);
    }

    public function getContains()
    {
        return $this->hasMany(CourseSections::className(), ['course_id' => 'id']);
    }


}
