<?php

namespace common\models;

use common\components\Gadget;
use common\modules\main\models\Category;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "packages".
 *
 * @property int $id
 * @property int $coach_id
 * @property int $category
 * @property string $name
 * @property int $discount
 * @property string|null $motive
 * @property string|null $description
 * @property int $start_register
 * @property int $end_register
 * @property int $start_date
 * @property int $period
 * @property string $poster
 * @property string $video
 * @property string|null $alt
 * @property string $status
 * @property $date
 */
class Packages extends ActiveRecord
{
    const STATUS_READY = 'ready';
    const STATUS_PREPARE = 'preparation';
    const STATUS_INACTIVE = 'inactive';

    const PREVIEW_ON = 1;
    const PREVIEW_OFF = 0;

    const UPLOAD_PATH = 'package';

    public $posterFile;
    public $videoFile;
    public $date;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'packages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coach_id', 'category', 'name', 'start_register', 'end_register', 'start_date', 'period'], 'required'],
            [['coach_id', 'category', 'discount', 'start_register', 'end_register', 'start_date', 'period', 'preview'], 'integer'],
            [['description'], 'string'],
            [['name', 'motive', 'poster', 'video', 'alt', 'status'], 'string', 'max' => 255],
            [['posterFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp'],
            [['videoFile'], 'file', 'extensions' => 'mp4'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'coach_id' => Yii::t('app', 'Coach ID'),
            'category' => Yii::t('app', 'دسته بندی'),
            'name' => Yii::t('app', 'نام پکیج'),
            'discount' => Yii::t('app', 'تخفیف'),
            'motive' => Yii::t('app', 'جمله انگیزشی'),
            'description' => Yii::t('app', 'توضیحات'),
            'start_register' => Yii::t('app', 'تاریخ شروع ثبت نام'),
            'end_register' => Yii::t('app', 'تاریخ پایان ثبت نام'),
            'start_date' => Yii::t('app', 'تاریخ شروع کلاس ها'),
            'period' => Yii::t('app', 'مدت زمان دوره'),
            'poster' => Yii::t('app', 'پوستر'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
            'status' => Yii::t('app', 'Status'),
            'posterFile' => Yii::t('app', 'بارگذاری فایل'),
            'videoFile' => Yii::t('app', 'بارگذاری ویدیو'),
            'date' => Yii::t('app', 'زمان بندی'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PackagesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PackagesQuery(get_called_class());
    }

    public function getCat()
    {
        return $this->hasOne(Category::className(), ['id' => 'category'])->andWhere(['belong' => Category::BELONG_COURSE]);
    }

    public function getCourses()
    {
        return $this->hasMany(Course::className(), ['package_id' => 'id']);
    }

    public function getDiet()
    {
        return $this->hasMany(Diet::className(), ['package_id' => 'id']);
    }

    public function getRegisters()
    {
        return $this->hasMany(Register::className(), ['package_id' => 'id'])->andWhere(['!=', 'factor_id', 0])
            ->andWhere(['payment' => Register::PAYMENT_ACCEPT]);
    }
}
