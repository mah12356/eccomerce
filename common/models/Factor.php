<?php

namespace common\models;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use Yii;

/**
 * This is the model class for table "factor".
 *
 * @property int $id
 * @property int $user_id
 * @property string $method
 * @property int $amount
 * @property string|null $response_key
 * @property string $payment
 * @property int $publish_date
 * @property int $update_date
 */
class Factor extends \yii\db\ActiveRecord
{
    const METHOD_ONLINE = 'online';
    const METHOD_OFFLINE = 'offline';

    const PAYMENT_PENDING = 'pending'; // در انتظار پرداخت
    const PAYMENT_ACCEPT = 'accept'; // پرداخت تایید شده
    const PAYMENT_REJECT = 'reject'; // پرداخت رد شده

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'factor';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'method', 'amount', 'publish_date', 'update_date'], 'required'],
            [['user_id', 'amount', 'publish_date', 'update_date'], 'integer'],
            [['method', 'response_key', 'payment'], 'string', 'max' => 255],
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
            'method' => Yii::t('app', 'نوع پرداخت'),
            'amount' => Yii::t('app', 'قیمت'),
            'response_key' => Yii::t('app', 'Response Key'),
            'payment' => Yii::t('app', 'وضعیت پرداخت'),
            'publish_date' => Yii::t('app', 'تاریخ صدور فاکتور'),
            'update_date' => Yii::t('app', 'Update Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return FactorQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new FactorQuery(get_called_class());
    }

    public static function deleteFactor($id)
    {
        $result = new Tools();

        $model = Factor::findOne(['id' => $id]);
        if (!$model) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'فاکتور مورد نظر یافت نشد';
            $result->response['alert'][$result->index] = 'factor not found';
            $result->index++;
            return $result->response;
        }

        if ($model->payment != self::PAYMENT_PENDING) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'شما اجازه حذف این فاکتور را ندارید';
            $result->response['alert'][$result->index] = 'not allowed to delete this factor';
            $result->index++;
            return $result->response;
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->delete()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'][$result->index] = 'خطا در حذف فاکتور';
                $result->response['alert'][$result->index] = 'failed to delete factor';
                $result->index++;
                return $result->response;
            }

            $register = Register::findOne(['factor_id' => $id]);
            if ($register) {
                if (!$register->delete()) {
                    $transaction->rollBack();
                    $result->response['error'] = true;
                    $result->response['message'][$result->index] = 'خطا در لغو پیش ثبت نام';
                    $result->response['alert'][$result->index] = 'failed to delete register';
                    $result->index++;
                    return $result->response;
                }

                if (Diet::findAll(['user_id' => $model->user_id, 'register_id' => $register->id]) &&
                    !Diet::deleteAll(['user_id' => $model->user_id, 'register_id' => $register->id])) {
                    $transaction->rollBack();
                    $result->response['error'] = true;
                    $result->response['message'][$result->index] = 'خطا در حذف رژیم ها';
                    $result->response['alert'][$result->index] = 'failed to delete diets';
                    $result->index++;
                    return $result->response;
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
        return $result->response;
    }

    public function getRegister()
    {
        return $this->hasOne(Register::className(), ['factor_id' => 'id']);
    }
}
