<?php

namespace common\components;

use common\components\KavenegarApi;
use common\modules\main\models\Config;
use yii\base\Component;

class SendSms extends Component
{

    public static function SMS($tell, $message)
    {
        $api = new \common\components\KavenegarApi('3477536232674450386C366A6C6F7253644D79514A7662554C61764B6B6450505A724365426745304832513D');
        $api->Send('90006812',$tell,$message);
        return true;
    }

    public static function SMSGroup($tell, $message)
    {
        $model = Config::find()->where(['key' => 'sms-key'])->asArray()->one();
        $api = new \common\components\KavenegarApi($model['content']);
        return $api->SendArray('30006703323323', $tell, $message);

    }

    public static function VSMS($tell, $template, $token, $token2 = null, $token3 = null)
    {
        try{
            $api = new \common\components\KavenegarApi('3477536232674450386C366A6C6F7253644D79514A7662554C61764B6B6450505A724365426745304832513D');
            return $api->VerifyLookup($tell, $template, $token, $token2, $token3);
        }catch (\Exception $e){
            return null;
        }
    }
}
