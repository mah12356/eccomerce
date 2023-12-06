<?php

namespace common\components;

use yii\base\Component;
use function Composer\Autoload\includeFile;
use common\models\Factor;


class IranKish extends Component
{
    public $token;
    public $requestId;
    private int $count = 1;
    const terminalID = '08138294';
    const password = 'F63858875047D2B1';
    const acceptorId = "992180008138294";
    const pub_key = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQClp9jhGpcZgGY3sxPcBYvs1qWl
ljydqB6uNj2k3E2lt20qwKfsJyXxWys2uh+aTGDoqbdSsJqHZqml/4ZiY4bTPNqC
aUEDSnz6LzhCpXz2NRuU4mLYhQER4NoyifqrNoc5jBc4LbOIuSZTnrIozPtgkNGp
DNERZWcVanBLZ9Y+7QIDAQAB
-----END PUBLIC KEY-----';
    const PAYMENT_RESULT = 'https://mahsaonlin.com/factor/payment-result';
    public $responseCodes = [
        '17' => 'از انجام تراکنش صرف نظر شد',
        '3' => 'پذیرنده فروشگاهی نا معتبر است',
        '64' => 'مبلغ تراکنش نادرست است،جمع مبالغ تقسیم وجوه برابر مبلغ کل تراکنش نمی باش',
        '94' => 'تراکنش تکراری است',
        '25' => 'تراکنش اصلی یافت نشد',
        '77' => 'روز مالی تراکنش نا معتبر است',
        '40' => 'عمل درخواستی پشتیبانی نمی شود',
        '97' => 'کد تولید کد اعتبار سنجی نا معتبر است',
        '30' => 'فرمت پیام نادرست است',
        '86' => 'شتاب در حالSign Offاست',
        '55' => 'رمز کارت نادرست است',
        '57' => 'انجام تراکنش مورد درخواست توسط پایانه انجام دهنده مجاز نمی باشد',
        '58' => 'انجام تراکنش مورد درخواست توسط پایانه انجام دهنده مجاز نمی باشد',
        '63' => 'تمهیدات امنیتی نقض گردیده است',
        '96' => 'قوانین سامانه نقض گردیده است ، خطای داخلی سامانه',
        '2' => 'تراکنش قبلا برگشت شده است',
        '54' => 'تاریخ انقضا کارت سررسید شده است',
        '62' => 'کارت محدود شده است',
        '75' => 'تعداد دفعات ورود رمز اشتباه از حد مجاز فراتر رفته است',
        '14' => 'اطلاعات کارت صحیح نمی باشد',
        '51' => 'موجودی حساب کافی نمی باشد',
        '56' => 'اطلاعات کارت یافت نشد',
        '61' => 'مبلغ تراکنش بیش از حد مجاز است',
        '65' => 'تعداد دفعات انجام تراکنش بیش از حد مجاز است',
        '78' => 'کارت فعال نیست',
        '79' => 'حساب متصل به کارت بسته یا دارای اشکال است',
        '42' => 'کارت یا حساب مقصد در وضعیت پذیرش نمیباشد',
        '31' => 'عدم تطابق کد ملی خریدار با دارنده کارت',
        '98' => 'سقف استفاده از رمز دوم ایستا به پایان رسیده است',
        '901' => 'درخواست نا معتبر است ',
        '902' => 'پارامترهای اضافی درخواست نامعتبر می باشد ',
        '903' => 'شناسه پرداخت نامعتبر می باشد ',
        '904' => 'اطلاعات مرتبط با قبض نا معتبر می باشد ',
        '905' => 'شناسه درخواست نامعتبر می باشد ',
        '906' => 'درخواست تاریخ گذشته',
        '907' => 'آدرس بازگشت نتیجه پرداخت نامعتبر می باشد ',
        '909' => 'پذیرنده نامعتبر می باشد',
        '910' => 'پارامترهای مورد انتظار پرداخت تسهیمی تامین نگردیده است',
        '911' => 'پارامترهای مورد انتظار پرداخت تسهیمی نا معتبر یا دارای اشکال می باشد',
        '912' => 'تراکنش درخواستی برای پذیرنده فعال نیست ',
        '913' => 'تراکنش تسهیم برای پذیرنده فعال نیست',
        '914' => 'آدرس آی پی دریافتی درخواست نا معتبر می باشد',
        '915' => 'شماره پایانه نامعتبر می باشد ',
        '916' => 'شماره پذیرنده نا معتبر می باشد ',
        '917' => 'نوع تراکنش اعلام شده در خواست نا معتبر می باشد ',
        '918' => 'پذیرنده فعال نیست',
        '919' => 'مبالغ تسهیمی ارائه شده با توجه به قوانین حاکم بر وضعیت تسهیم پذیرنده،نا معتبر است',
        '920' => 'شناسه نشانه نامعتبر می باشد',
        '921' => 'شناسه نشانه نامعتبر و یا منقضی شده است',
        '922' => 'نقض امنیت درخواست',
        '923' => 'ارسال شناسه پرداخت در تراکنش قبض مجاز نیست',
        '928' => 'مبلغ مبادله شده نا معتبر می باشد',
        '929' => 'شناسه پرداخت ارائه شده با توجه به الگوریتم متناظر نا معتبر می باشد',
        '930' => 'کد ملی ارائه شده نا معتبر می باشد',
    ];

    protected function generateAuthenticationEnvelope($amount): array
    {
        $data = $this::terminalID . $this::password . str_pad($amount, 12, '0', STR_PAD_LEFT) . '00';
        $data = hex2bin($data);
        $AESSecretKey = openssl_random_pseudo_bytes(16);
        $ivlen = openssl_cipher_iv_length($cipher = "AES-128-CBC");
        $iv = openssl_random_pseudo_bytes($ivlen);
        $ciphertext_raw = openssl_encrypt($data, $cipher, $AESSecretKey, $options = OPENSSL_RAW_DATA, $iv);
        $hmac = hash('sha256', $ciphertext_raw, true);
        $crypttext = '';

        openssl_public_encrypt($AESSecretKey . $hmac, $crypttext, $this::pub_key);

        return array(
            "data" => bin2hex($crypttext),
            "iv" => bin2hex($iv),
        );
    }

    public function tokenGenerator($amount, $address = null): bool
    {
        if (!$address) {
            $address = self::PAYMENT_RESULT;
        }

        $token = $this->generateAuthenticationEnvelope($amount);
        $this->requestId = uniqid();
        $data = [];
        $data["request"] = [
            "acceptorId" => $this::acceptorId,
            "amount" => $amount,
            "billInfo" => null,

            "paymentId" => null,
            "requestId" => $this->requestId,
            "requestTimestamp" => time(),
            "revertUri" => $address,
            "terminalId" => $this::terminalID,
            "transactionType" => "Purchase"
        ];
        $data['authenticationEnvelope'] = $token;
        $data_string = json_encode($data);
        $ch = curl_init('https://ikc.shaparak.ir/api/v3/tokenization/make');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));


        $result = curl_exec($ch);

        curl_close($ch);

        $response = json_decode($result, JSON_OBJECT_AS_ARRAY);
        if ($response["responseCode"] != "00") {
            return false;
        }
        $this->token = $response['result']["token"];
        return true;
    }

    public function checkResult($response): array
    {

        $data = array(
            "terminalId" => self::terminalID,
            "retrievalReferenceNumber" => $response['retrievalReferenceNumber'],
            "systemTraceAuditNumber" => $response['systemTraceAuditNumber'],
            "tokenIdentity" => $response['token'],
        );

        $data_string = json_encode($data);

        $ch = curl_init('https://ikc.shaparak.ir/api/v3/confirmation/purchase');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string)
        ));

        $result = curl_exec($ch);
        if ($result === false) {
            while ($this->count <= 20) {
                sleep(1);
                ++$this->count;
                $this->checkResult($response);
            }
            return array(false, curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($result, JSON_OBJECT_AS_ARRAY);
        return array(true, $response);

    }


    public function startPurchase($price): bool
    {

        return $firstResult = $this->tokenGenerator($price);

    }

    public function finalResult($data)
    {
        if (isset($this->responseCodes[$data])) {
            return $this->responseCodes[$data];
        } else {
            return 'Unknown error Call Support team';

        }
    }

    public function checkPayments()
    {
        $now = Jdf::jmktime();
        $model = Payment::find()->where(['<=', 'date', $now])->andWhere(['!=', 'status', 5])->all();
        if (!$model) {
            return;
        }
        foreach ($model as $payment) {
            $tran = \Yii::$app->db->beginTransaction();
            $factor = Factor::find()->where(['id' => $payment->factorId])->one();
            if (!$factor) {
                continue;
            }
            if ($factor->status == "factorPaid") {
                if (!$payment->delete()) {
                    $payment->status = 1;
                    if (!$payment->save()) {
                        $tran->rollBack();
                        continue;
                    }
                }
                $tran->commit();
                continue;
            }

            $data = array(
                "passPhrase" => $this::password,
                "terminalId" => $this::terminalID,
                "retrievalReferenceNumber" => null,
                "tokenIdentity" => null,
                "requestId" => $payment->requestId,
                "findOption" => 3,
            );

            $data_string = json_encode($data);
            $ch = curl_init('https://ikc.shaparak.ir/api/v3/inquiry/single');
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string)
            ));

            $result = curl_exec($ch);
            curl_close($ch);

            if ($result === false) {
                $payment->status = 2;
                if (!$payment->save()) {
                    $tran->rollBack();
                    continue;
                }
                $tran->commit();
                continue;
            }
            $response = json_decode($result, JSON_OBJECT_AS_ARRAY);
            if ($response['responseCode'] == '00') {
                $checkResult = $this->checkResult($response['Result']);
                if ($checkResult[0]) {
                    $factor->status = "factorPaid";
                    if (!$factor->save()) {
                        $payment->status = 3;
                        if (!$payment->save()) {
                            $tran->rollBack();
                            continue;
                        }
                        $tran->commit();
                        continue;
                    }
                    $activations = Activation::find()->where(['idFactor' => $factor->id])->all();
                    if (!$activations) {
                        $tran->rollBack();
                        continue;
                    }
                    $res = true;
                    foreach ($activations as $activation) {
                        $activation->active = "factorPaid";
                        if (!$activation->save()) {
                            $res = false;
                            $tran->rollBack();
                            break;
                        }
                    }
                    if (!$res) {
                        continue;
                    }
                } else {
                    $payment->status = 4;
                    if (!$payment->save()) {
                        $tran->rollBack();
                        continue;
                    }
                    $tran->commit();
                    continue;
                }
                $tran->commit();
                if (!$payment->delete()) {
                    $payment->status = 5;
                    if (!$payment->save()) {
                        continue;
                    }
                }
            }


        }
    }

}