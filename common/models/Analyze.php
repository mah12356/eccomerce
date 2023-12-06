<?php

namespace common\models;

use common\components\Gadget;
use Yii;

/**
 * This is the model class for table "analyze".
 *
 * @property int $id
 * @property int $user_id
 * @property int $register_id
 * @property int $package_id
 * @property int $course_id
 * @property string $height
 * @property string $weight
 * @property string $age
 * @property string $gender
 * @property string $wrist
 * @property string $arm
 * @property string $chest
 * @property string $under_chest
 * @property string $belly
 * @property string $waist
 * @property string $hip
 * @property string $thigh
 * @property string $shin
 * @property string $front_image
 * @property string $side_image
 * @property string $back_image
 * @property string $status
 * @property int $date
 * @property int $updated_at
 */
class Analyze extends \yii\db\ActiveRecord
{
    const GENDER_MALE = 'مرد';
    const GENDER_FEMALE = 'زن';


    const STATUS_INACTIVE = 'inactive'; // غیرفعال و منتظر پرداخت
    const STATUS_ACTIVE = 'active'; // فعال و در انتظار پاسخ
    const STATUS_ANSWERED = 'answered'; // کاربر پاسخ داده

    const UPLOAD_PATH = 'analyze';

    public $front;
    public $back;
    public $side;

    // filters
    public $name;
    public $lastname;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'analyze';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'register_id', 'package_id', 'course_id', 'date', 'updated_at'], 'required'],
            [['user_id', 'register_id', 'package_id', 'course_id', 'date', 'updated_at'], 'integer'],
            [['height', 'weight', 'age', 'gender', 'wrist', 'arm', 'chest', 'under_chest', 'belly', 'waist', 'hip', 'thigh', 'shin', 'front_image', 'side_image', 'back_image', 'status'], 'string', 'max' => 255],
            [['front', 'back', 'side'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['height', 'weight', 'age', 'wrist', 'arm', 'chest', 'under_chest', 'belly', 'waist', 'hip', 'thigh', 'shin'], 'validateNumeric'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'register_id' => Yii::t('app', 'Register ID'),
            'package_id' => Yii::t('app', 'پکیج'),
            'course_id' => Yii::t('app', 'Course ID'),
            'height' => Yii::t('app', 'قد'),
            'weight' => Yii::t('app', 'وزن'),
            'age' => Yii::t('app', 'سن'),
            'gender' => Yii::t('app', 'جنسیت'),
            'wrist' => Yii::t('app', 'دور مچ'),
            'arm' => Yii::t('app', 'دور بازو'),
            'chest' => Yii::t('app', 'دور سینه'),
            'under_chest' => Yii::t('app', 'دور زیر سینه'),
            'belly' => Yii::t('app', 'دور شکم'),
            'waist' => Yii::t('app', 'دور کمر'),
            'hip' => Yii::t('app', 'دور باسن'),
            'thigh' => Yii::t('app', 'دور ران'),
            'shin' => Yii::t('app', 'دور ساق'),
            'front' => Yii::t('app', 'عکس از جلو'),
            'side' => Yii::t('app', 'عکس از کنار'),
            'back' => Yii::t('app', 'عکس از پشت'),
            'status' => Yii::t('app', 'وضعیت'),
            'date' => Yii::t('app', 'زمان ثبت'),
            'updated_at' => Yii::t('app', 'Updated At'),

            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AnalyzeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AnalyzeQuery(get_called_class());
    }

    public function validateNumeric($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->$attribute = Gadget::convertToEnglish($this->$attribute);
            if (!is_numeric($this->$attribute)){
                $this->addError($attribute, $this->getAttributeLabel($attribute) . ' باید عدد باشد');
            }
        }
    }

    public static function getGender()
    {
        return [
            self::GENDER_FEMALE => 'زن',
            self::GENDER_MALE => 'مرد',
        ];
    }

    public static function getStatus()
    {
        return [
            self::STATUS_INACTIVE => 'غیر فعال',
            self::STATUS_ACTIVE => 'فعال',
            self::STATUS_ANSWERED => 'پاسخ داده شده',
        ];
    }

    public function getPackage()
    {
        return $this->hasOne(Packages::className(), ['id' => 'package_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
