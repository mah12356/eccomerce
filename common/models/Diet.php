<?php

namespace common\models;

use common\components\Gadget;
use common\components\Jdf;
use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "diet".
 *
 * @property int $id
 * @property int $user_id
 * @property int $register_id
 * @property int $package_id
 * @property int $course_id
 * @property int $regime_id
 * @property string $type
 * @property string $status
 * @property int $date
 * @property int $date_update
 *
 * @property string $name
 * @property string $lastname
 */
class Diet extends \yii\db\ActiveRecord
{
    const TYPE_DIET = 'diet'; // رژیم غذایی
    const TYPE_SHOCK = 'shock'; // رژیم شوک

    const STATUS_PENDING = 'pending'; //
    const STATUS_WAIT_FOR_ANSWERS = 'wait for answers'; // منتظر پاسخ به سوالات
    const STATUS_WAIT_FOR_RESPONSE = 'wait for response'; // منتظر مربی برای بارگذاری رژیم
    const STATUS_REGIME_UPLOADED = 'regime uploaded'; // رژیم بارگذاری شد
    const STATUS_REGIME_NOT_FOUND = 'regime not found'; // رژیمی یافت نشد

    // این متغیر ها برای فیلتر کردن اطلاعات است
    public $name;
    public $lastname;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'diet';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'register_id', 'package_id', 'course_id', 'date', 'date_update'], 'required'],
            [['user_id', 'package_id', 'course_id', 'regime_id', 'date', 'date_update'], 'integer'],
            [['status', 'name', 'lastname'], 'string', 'max' => 255],
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
            'package_id' => Yii::t('app', 'Package ID'),
            'course_id' => Yii::t('app', 'Course ID'),
            'regime_id' => Yii::t('app', 'Regime ID'),
            'status' => Yii::t('app', 'وضعیت'),
            'date' => Yii::t('app', 'تاریخ ثبت'),
            'date_update' => Yii::t('app', 'تاریخ آخرین ویرایش'),

            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DietQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DietQuery(get_called_class());
    }

    public static function getStatus()
    {
        return [
            self::STATUS_WAIT_FOR_ANSWERS => 'در انتظار پاسخ به سوالات',
            self::STATUS_WAIT_FOR_RESPONSE => 'در انتظار بارگذاری برنامه',
            self::STATUS_REGIME_NOT_FOUND => 'برنامه غذایی مناسب پیدا نشد',
        ];
    }

    // فعالسازی رژیم ها و آماده سازی آنها برای پاسخ دادن
    public static function activeDiets($user_id, $package_id)
    {
        $register = Register::find()->where([
            'user_id' => $user_id,
            'payment' => Register::PAYMENT_ACCEPT,
        ])->andWhere(['IN', 'package_id', $package_id])->asArray()->all();
        
        $model = Diet::find()->where(['user_id' => $user_id, 'status' => Diet::STATUS_PENDING])
            ->andWhere(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])->all();
//دو روز موقتا اضافه شده باید بعدا برداشته شود
        $currentTimeStamp = Jdf::jmktime() + 259200;

        foreach ($model as $item) {
//            Gadget::preview($item->date , false);
//            Gadget::preview($currentTimeStamp);
            if ($item->date <= $currentTimeStamp) {
                $item->status = Diet::STATUS_WAIT_FOR_ANSWERS;
                $item->date_update = $currentTimeStamp;
                $item->save();
            }
        }
    }
    
    // بارگذاری رژیم هایی که به سوالات آنها پاسخ داده شده است
    public static function assignRegimes()
    {
        $activePackages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $activeRegister = Register::find()->where(['payment' => Register::PAYMENT_ACCEPT])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($activePackages, 'id', 'id')])
            ->asArray()->all();
        $model = Diet::find()->where(['status' => Diet::STATUS_WAIT_FOR_RESPONSE])
            ->andWhere(['IN', 'register_id', ArrayHelper::map($activeRegister, 'id', 'id')])
            ->with('answers.options')->asArray()->all();

        $age_question = 2;
        $height_question = 3;
        $weight_question = 4;
        $gender_question = 9;
        $activity_question = 7;


        foreach ($model as $item) {
            $age = 27;
            $height = 160;
            $weight = 70;
            $gender = 'female';
            $activity = 'low';

            if ($item['answers']) {
                foreach ($item['answers'] as $answer) {
                    switch ($answer['question_id']) {
                        case $age_question:
                            $age = (int)Gadget::convertToEnglish($answer['response']);
                            break;
                        case $height_question:
                            $height = (int)Gadget::convertToEnglish($answer['response']);
                            break;
                        case $weight_question:
                            $weight = (int)Gadget::convertToEnglish($answer['response']);
                            break;
                        case $gender_question:
                            if ($answer['options']) {
                                foreach ($answer['options'] as $option) {
                                    if ($option['option_id'] == 14) {
                                        $gender = 'male';
                                    }
                                }
                            }
                            break;
                        case $activity_question:
                            if ($answer['options']) {
                                foreach ($answer['options'] as $option) {
                                    if ($option['option_id'] == 29) {
                                        $activity = 'low';
                                    }elseif ($option['option_id'] == 30) {
                                        $activity = 'medium';
                                    }else {
                                        $activity = 'high';
                                    }
                                }
                            }
                            break;
                        default:
                            break;
                    }
                }
            }

            self::calculator($item['id'], $age, $height, $weight, $gender, $activity);
        }
    }

    public static function calculator($diet_id, $age, $height, $weight, $gender, $activity)
    {
        $model = Diet::findOne(['id' => $diet_id]);

        if ($gender == 'male') {
            $basicEnergy = 66.47 + (13.75 * $weight) + (5 * $height) - (6.76 * $age);
        }else {
            $basicEnergy = 65.5 + (9.56 * $weight) + (1.85 * $height) - (4.68 * $age);
        }

//        if ($activity == 'low') {
//            $calorie = $basicEnergy * 1.375;
//        }elseif ($activity == 'medium') {
//            $calorie = $basicEnergy * 1.6;
//        }else {
//            $calorie = $basicEnergy * 1.8;
//        }

        $calorie = $basicEnergy * 1.375;

        $calorie = round((int)$calorie , -2);

        if ($calorie < 1200) {
            $calorie = 1200;
        }
        if ($calorie > 1900) {
            $calorie = 1900;
        }
        if($model->type == Diet::TYPE_SHOCK){
            $calorie = 0 ;
        }

        $diets = Diet::find()->where(['user_id' => $model->user_id, 'status' => Diet::STATUS_REGIME_UPLOADED])->asArray()->all();

        if ($diets) {

            $regimes = Regimes::find()->where(['NOT IN', 'id', ArrayHelper::map($diets, 'regime_id', 'regime_id')])
                ->andWhere(['type' => $model->type, 'calorie' => $calorie, 'status' => Regimes::STATUS_ACTIVE])->asArray()->all();
        }else {
            $regimes = Regimes::find()->where(['type' => $model->type, 'calorie' => $calorie, 'status' => Regimes::STATUS_ACTIVE])
                ->asArray()->all();
        }

        if ($regimes) {
            $pr = rand(0, count($regimes) - 1);
            $model->regime_id = $regimes[$pr]['id'];
            $model->status = self::STATUS_REGIME_UPLOADED;
            $model->date_update = Jdf::jmktime();
        }else {
            $model->status = self::STATUS_REGIME_NOT_FOUND;
        }

        $model->save();
    }

    public function getCourse()
    {
        return $this->hasOne(Course::className(), ['id' => 'course_id']);
    }

    public function getAnswers()
    {
        return $this->hasMany(Answers::className(), ['diet_id' => 'id']);
    }

    public function getRegime()
    {
        return $this->hasOne(Regimes::className(), ['id' => 'regime_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getPackage()
    {
        return $this->hasOne(Packages::className(), ['id' => 'package_id']);
    }
}
