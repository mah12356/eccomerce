<?php

namespace common\modules\main\models;

use common\components\Gadget;
use common\components\Jdf;
use common\components\SendSms;
use Yii;

/**
 * This is the model class for table "tokens".
 *
 * @property int $id
 * @property string $mobile
 * @property int $code
 * @property int $sent_at
 */
class Tokens extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tokens';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mobile', 'code', 'sent_at'], 'required'],
            [['sent_at'], 'integer'],
            [['mobile'], 'string', 'max' => 11],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'mobile' => Yii::t('app', 'Mobile'),
            'code' => Yii::t('app', 'کد تایید'),
            'sent_at' => Yii::t('app', 'Sent At'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TokensQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TokensQuery(get_called_class());
    }

    public static function sendVerifyCode($mobile, $debug)
    {
        $model = new Tokens();

        $model->mobile = $mobile;
        if ($debug) {
            $model->code = '1945';
        }else {
            $model->code = substr(str_shuffle("0123456789"), 0, 4);
        }

        $model->sent_at = Jdf::jmktime();
        if ($model->save(false)) {
            if (!$debug) {
                SendSms::VSMS($mobile, 'test', $model->code, null, null);
            }
        }
    }
}
