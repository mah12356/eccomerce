<?php

namespace common\models;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\SendSms;
use common\components\Tools;
use Yii;

/**
 * This is the model class for table "register".
 *
 * @property int $id
 * @property int $user_id
 * @property int $package_id
 * @property int $factor_id
 * @property string $payment
 * @property string $status
 */
class Register extends \yii\db\ActiveRecord
{
    const PAYMENT_PENDING = 'pending'; // در انتظار پرداخت
    const PAYMENT_ACCEPT = 'accept'; // پرداخت تایید شده
    const PAYMENT_REJECT = 'reject'; // پرداخت رد شده

    const STATUS_PENDING = 'pending'; // در انتظار فعالسازی
    const STATUS_PROCESSING = 'processing'; // در حال برگذاری
    const STATUS_FINISHED = 'finished'; // پایان یافته
    const STATUS_REJECT = 'reject'; // رد شده

    public $name;
    public $lastname;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'register';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'package_id'], 'required'],
            [['user_id', 'package_id', 'factor_id'], 'integer'],
            [['payment', 'status'], 'string', 'max' => 255],
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
            'package_id' => Yii::t('app', 'پکیج'),
            'factor_id' => Yii::t('app', 'Factor ID'),
            'payment' => Yii::t('app', 'Payment'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return RegisterQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RegisterQuery(get_called_class());
    }

    // رزرو و ثبت نام کاربر در یک پکیج به همراه دوره های مختلف آن پکیج
    public function enroll(int $user_id, int $package_id, array $courses, $manual = false): array
    {
        $result = new Tools();

        // کسب اطمینان از وجود پکیج
        $package = Packages::find()->where(['id' => $package_id, 'status' => Packages::STATUS_READY])->asArray()->one();
        if (!$package) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'پکیج مورد نظر یافت نشد';
            $result->response['alert'][$result->index] = 'package not found';
            $result->index++;
            return $result->response;
        }

        // در صورت ثبت نام توسط ادمین، بازه زمانی ثبت نام بررسی نمیشود
        if (!$manual) {
            if ($package['end_register'] < Jdf::jmktime()) {
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'زمان ثبت نام پکیج به اتمام رسیده است';
                $result->response['alert'][$result->index] = 'enroll period expired';
                $result->index++;
                return $result->response;
            }
        }

        // بررسی تکراری نبودن
        $register = Register::find()->where(['user_id' => $user_id, 'package_id' => $package_id, 'payment' => Register::PAYMENT_ACCEPT])->andWhere([])->asArray()->one();
        if ($register) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'شما در این دوره ثبت نام کرده اید';
            $result->response['alert'][$result->index] = 'duplicate package selected';
            $result->index++;
            return $result->response;
        }

        $amount = 0;
        foreach ($courses as $course) {
            $amount += (int)$course['price'];
        }

        if ($amount == 0) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'خطا در محاسبه قیمت';
            $result->response['alert'][$result->index] = 'failed to calculate price';
            $result->index++;
            return $result->response;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            // صدور فاکتور
            $factor = new Factor();

            $factor->user_id = $user_id;
            $factor->method = Factor::METHOD_ONLINE;
            $factor->amount = Gadget::calculateDiscount($amount, $package['discount']);
            $factor->publish_date = Jdf::jmktime();
            $factor->update_date = Jdf::jmktime();

            if (!$factor->save()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'خطا در صدور فاکتور';
                $result->response['alert'][$result->index] = 'failed to issue factor';
                $result->index++;
                return $result->response;
            }

            $this->user_id = $user_id;
            $this->package_id = $package_id;
            $this->factor_id = $factor->id;

            // ذخیره پکیج ثبت نام شده برای کاربر
            if (!$this->save()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'خطا در ثبت نام پکیج';
                $result->response['alert'][$result->index] = 'failed to enroll package';
                $result->index++;
                return $result->response;
            }

            // ثبت دوره های انتخابی از پکیج
            foreach ($courses as $course) {
                $registerCourses = new RegisterCourses();

                $registerCourses->register_id = $this->id;
                $registerCourses->course_id = $course['id'];

                if (!$registerCourses->save()) {
                    $transaction->rollBack();
                    $result->response['error'] = true;
                    $result->response['message'][$result->index] = 'خطا در ثبت دوره های انتخابی';
                    $result->response['alert'][$result->index] = 'failed to save selected courses';
                    $result->index++;
                    return $result->response;
                }
            }

            foreach ($courses as $course) {
                // ثبت رژیم برای کاربر
                if ($course['belong'] == Course::BELONG_DIET) {
                    $j = 0;
                    $interval = (int)($package['period'] / $course['count']);

                    for ($i = 1; $i <= $course['count']; $i++) {
                        $diet = new Diet();

                        $diet->user_id = $user_id;
                        $diet->register_id = $this->id;
                        $diet->package_id = $package_id;
                        $diet->course_id = $course['id'];
                        $diet->type = Diet::TYPE_DIET;

                        $timeDifference = $j * $interval * 86400;

                        $diet->date = Jdf::jmktime() + $timeDifference;
                        $diet->date_update = $diet->date;

                        if (!$diet->save()) {
                            $transaction->rollBack();
                            $result->response['error'] = true;
                            $result->response['message'][$result->index] = 'خطا در ثبت برنامه های غذایی';
                            $result->response['alert'][$result->index] = 'failed to execute diet programs';
                            $result->index++;
                            return $result->response;
                        }

                        $j++;
                    }
                }

                // ثبت رژیم شوک برای کاربر
                if ($course['belong'] == Course::BELONG_SHOCK_DIET) {
                    $j = 0;
                    $interval = (int)($package['period'] / $course['count']);

                    for ($i = 1; $i <= $course['count']; $i++) {
                        $diet = new Diet();

                        $diet->user_id = $user_id;
                        $diet->register_id = $this->id;
                        $diet->package_id = $package_id;
                        $diet->course_id = $course['id'];
                        $diet->type = Diet::TYPE_SHOCK;

                        $timeDifference = $j * $interval * 86400;

                        $diet->date = Jdf::jmktime() + $timeDifference + (14 * 86400);
                        $diet->date_update = $diet->date;

                        if (!$diet->save()) {
                            $transaction->rollBack();
                            $result->response['error'] = true;
                            $result->response['message'][$result->index] = 'خطا در ثبت رژیم شوک';
                            $result->response['alert'][$result->index] = 'failed to execute shock diet programs';
                            $result->index++;
                            return $result->response;
                        }

                        $j++;
                    }
                }

                // ثبت آنالیز
                if ($course['belong'] == Course::BELONG_ANALYZE) {
                    $analyze = new Analyze();

                    $analyze->user_id = $user_id;
                    $analyze->register_id = $this->id;
                    $analyze->package_id = $this->package_id;
                    $analyze->course_id = $course['id'];
                    $analyze->date = Jdf::jmktime();
                    $analyze->updated_at = $analyze->date;

                    $analyze->save(false);
                }
            }
        }catch (\Exception $exception) {
            $transaction->rollBack();
            $result->response['error'] = true;
            $result->response['message'][$result->index] = Message::UNKNOWN_ERROR;
            $result->response['alert'][$result->index] = Alerts::UNKNOWN_ERROR;
            $result->index++;
            return $result->response;
        }

        $transaction->commit();
        $result->response['data']['factor_id'] = $factor->id;
        return $result->response;
    }

    // تغییر وضعیت فاکتور و دوره های ثبت نامی
    public static function enrollPayment($payment, $factor_id)
    {
        $result = new Tools();

        $factor = Factor::findOne(['id' => $factor_id]);
        if (!$factor) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = Message::MODEL_NOT_FOUND;
            $result->response['alert'][$result->index] = Alerts::USER_NOT_FOUND;
            $result->index++;
            return $result->response;
        }

        $register = Register::findOne(['factor_id' => $factor_id]);
        if (!$register) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = Message::MODEL_NOT_FOUND;
            $result->response['alert'][$result->index] = Alerts::USER_NOT_FOUND;
            $result->index++;
            return $result->response;
        }

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($payment) {
                $factor->payment = Factor::PAYMENT_ACCEPT;
                $factor->update_date = Jdf::jmktime();

                $register->payment = Register::PAYMENT_ACCEPT;
                $register->status = Register::STATUS_PROCESSING;
            }else {
                $factor->payment = Factor::PAYMENT_REJECT;
                $factor->update_date = Jdf::jmktime();

                $register->payment = Register::PAYMENT_REJECT;
                $register->status = Register::STATUS_REJECT;
            }

            if (!$factor->save()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'خطا در ویرایش فاکتور';
                $result->response['alert'][$result->index] = 'factor update failed';
                $result->index++;
                return $result->response;
            }

            if (!$register->save()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'خطا در اتمام فرآیند ثبت نام';
                $result->response['alert'][$result->index] = 'enroll updates failed';
                $result->index++;
                return $result->response;
            }
            $uu =  User::find()->where(['id'=>$register->user_id])->one();
            $message = 'مهساآنلاینی عزیز، دوره درخواستی  ' . '  برای شما با موفقیت فعال شد
لغو ۱۱';

            SendSms::SMS($uu->username, $message);
        }catch (\Exception $exception) {
            $transaction->rollBack();
            $result->response['error'] = true;
            $result->response['message'][$result->index] = Message::UNKNOWN_ERROR;
            $result->response['alert'][$result->index] = Alerts::UNKNOWN_ERROR;
            $result->index++;
            return $result->response;
        }

        $transaction->commit();
        return $result->response;
    }

    // حذف یک کاربر از یک پکیج
    public static function revoke($id): bool
    {
        try {
            $transaction = Yii::$app->db->beginTransaction();
            $model = Register::findOne(['id' => $id]);
            $factor = Factor::findOne(['id' => $model->factor_id]);
            if ($factor) {
                if (!$factor->delete()) {
                    $transaction->rollBack();
                    return false;
                }
            }
            if (!RegisterCourses::deleteAll(['register_id' => $id])) {
                $transaction->rollBack();
                return false;
            }
            if (!Diet::deleteAll(['user_id' => $model->user_id, 'register_id' => $model->id, 'package_id' => $model->package_id])) {
                $transaction->rollBack();
                return false;
            }
            if (!$model->delete()) {
                $transaction->rollBack();
                return false;
            }
            $transaction->commit();
            return true;
        }catch (\Exception $exception) {
            return false;
        }
    }
    
    public function getPackage()
    {
        return $this->hasOne(Packages::className(), ['id' => 'package_id']);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getCourses()
    {
        return $this->hasMany(RegisterCourses::className(), ['register_id' => 'id']);
    }
}
