<?php

namespace common\models;

use common\components\Gadget;
use common\components\Jdf;
use Yii;

/**
 * This is the model class for table "booking".
 *
 * @property int $id
 * @property string $name
 * @property string $mobile
 * @property string $description
 * @property int $month
 * @property int $date
 * @property string $status
 */
class Booking extends \yii\db\ActiveRecord
{
    const STATUS_PENDING = 'pending';
    const STATUS_CHECKED = 'checked';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'booking';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'mobile', 'month', 'date'], 'required'],
            [['description'], 'string'],
            [['month', 'date'], 'integer'],
            [['name', 'mobile', 'status'], 'string', 'max' => 255],
            ['mobile', 'validateMobile'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'نام و نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره تماس'),
            'description' => Yii::t('app', 'توضیحات'),
            'month' => Yii::t('app', 'تاریخ ثبت تماس'),
            'date' => Yii::t('app', 'Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    public function validateMobile($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $this->mobile = Gadget::convertToEnglish($this->mobile);
            $numbers = str_split($this->mobile);
            if ($numbers[0] == '#') {
                $this->mobile = str_replace('#', '0', $this->mobile);
                if (!is_numeric($this->mobile) || count($numbers) != 11 || $numbers[1] != 9) {
                    $this->addError($attribute, 'شماره موبایل وارد شده صحیح نمی‌باشد');
                }
            }elseif (!is_numeric($this->mobile) || count($numbers) != 11 && $numbers[0] != 0 || $numbers[1] != 9) {
                $this->addError($attribute, 'شماره موبایل وارد شده صحیح نمی‌باشد');
            }
        }
    }

    public function saveCall()
    {
        $this->date = Jdf::jmktime();
        $this->month = Jdf::jmktime(0,0, 0, Jdf::jdate('m', $this->date), 1, Jdf::jdate('Y', $this->date));

        if ($this->validate() && $this->save()) {
            return true;
        }

        return false;
    }
}
