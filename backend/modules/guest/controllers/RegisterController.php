<?php

namespace backend\modules\guest\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use common\models\SignupForm;
use common\models\User;
use common\modules\main\models\Tokens;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class RegisterController extends ActiveController
{
    public $modelClass = 'common\models\User';
    public object $result;

    public function beforeAction($action)
    {
        $this->result = new Tools();
        return parent::beforeAction($action);
    }
    // دریافت شماره موبایل کاربر و ارسال کد تایید
    public function actionGetMobile()
    {
        $post = Gadget::setPost();

        if (isset($post['mobile']) && $post['mobile']) {
            $validation = Gadget::validateMobileFormat($post['mobile']);
            if (!$validation['error']) {
                Tokens::sendVerifyCode($validation['mobile'], $validation['debug']);
            }else {
                $this->result->response['error'] = true;
                $this->result->response['message'] = Message::WRONG_MOBILE;
                $this->result->response['alert'] = Alerts::WRONG_MOBILE;
                $this->result->index++;
            }
        }else {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شماره موبایل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** mobile **';
            $this->result->index++;
        }

        return $this->result->response;
    }
    // دریافت کد تایید ارسال شده به شماره موبایل کاربر
    public function actionVerify()
    {
        $post = Gadget::setPost();

        if (!isset($post['mobile']) || !$post['mobile']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'شماره موبایل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** mobile **';
            $this->result->index++;
        }
        if (!isset($post['code']) || !$post['code']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'کد تایید' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** code **';
            $this->result->index++;
        }
        if ($this->result->response['error']){
            return $this->result->response;
        }

        $validation = Gadget::validateMobileFormat($post['mobile']);
        if (!$validation['error']) {

            $model = Tokens::findOne(['mobile' => $validation['mobile'], 'code' => Gadget::convertToEnglish($post['code'])]);
            if ($model) {
                $user = User::findOne(['username' => $validation['mobile']]);
                if ($user) {
                    $this->result->response['data']['auth'] = $user->auth_key;
                    $this->result->response['data']['signup'] = false;
                }else {
                    $this->result->response['data']['auth'] = null;
                    $this->result->response['data']['signup'] = true;
                }
            }else {
                $this->result->response['error'] = true;
                $this->result->response['message'][$this->result->index] = Message::WRONG_VERIFY_CODE;
                $this->result->response['alert'][$this->result->index] = Alerts::WRONG_VERIFY_CODE;
                $this->result->index++;
            }
        }else{
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = Message::WRONG_MOBILE;
            $this->result->response['alert'][$this->result->index] = Alerts::WRONG_MOBILE;
            $this->result->index++;
        }
        return $this->result->response;
    }
    // ثبت نام کاربر
    public function actionSignup()
    {
        $post = Gadget::setPost();

        if (!isset($post['mobile']) || !$post['mobile']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'شماره موبایل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** mobile **';
            $this->result->index++;
        }
        if (!isset($post['name']) || !$post['name']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'نام' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** name **';
            $this->result->index++;
        }
        if (!isset($post['lastname']) || !$post['lastname']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'نام خانوادگی' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** lastname **';
            $this->result->index++;
        }
        if (!isset($post['email'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'ایمیل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** email **';
            $this->result->index++;
        }
        if (!isset($post['role']) || !$post['role']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'نقش کاربر' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** role **';
            $this->result->index++;
        }
        if ($this->result->response['error']){
            return $this->result->response;
        }
        if ($post['role'] != User::ROLE_COACH && $post['role'] != User::ROLE_USER) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'نقش کاربر به درستی انتخاب نشده است';
            $this->result->response['alert'][$this->result->index] = 'role is not correct';
            $this->result->index++;
            return $this->result->response;
        }

        $validate = Gadget::validateMobileFormat($post['mobile']);
        if (!$validate['error']) {
            $user = User::findOne(['username' => $validate['mobile']]);
            if (!$user) {
                $model = new SignupForm();

                $model->username = $post['mobile'];
                $model->name = $post['name'];
                $model->lastname = $post['lastname'];
                $model->mobile = $validate['mobile'];
                if ($post['email'] != null) {
                    $model->email = $post['email'];
                }else {
                    $model->email = $validate['mobile'] . '@gmail.com';
                }
                $model->password = $validate['mobile'];
                $model->role = $post['role'];

                if ($model->validate()) {
//                    return $model->errors;
                    if ($model->signup()) {
                        $user = User::findOne(['username' => $validate['mobile']]);
                        $this->result->response['data']['auth'] = $user->auth_key;
                    }else {
                        $this->result->response['error'] = true;
                        $this->result->response['message'][$this->result->index] = Message::FAILED_TO_EXECUTE;
                        $this->result->response['alert'][$this->result->index] = Alerts::PROCESS_INCOMPLETE;
                        $this->result->index++;
                    }
                } else {
                    $messages = ArrayHelper::getColumn($model->errors , 0, false);
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = $messages[0];
                    $this->result->response['alert'][$this->result->index] = Alerts::PROCESS_INCOMPLETE;
                    $this->result->index++;
                }
            }else{
                $this->result->response['error'] = true;
                $this->result->response['message'][$this->result->index] = Message::DUPLICATE_MOBILE;
                $this->result->response['alert'][$this->result->index] = Alerts::DUPLICATE_MOBILE;
                $this->result->index++;
            }
        }else {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = Message::WRONG_MOBILE;
            $this->result->response['alert'][$this->result->index] = Alerts::WRONG_MOBILE;
            $this->result->index++;
        }
        return $this->result->response;
    }
    // ارسال مجدد کد تایید به شماره کاربر
    public function actionResendCode()
    {
        $post = Gadget::setPost();

        if (isset($post['mobile']) && $post['mobile']) {
            $validate = Gadget::validateMobileFormat($post['mobile']);
            if (!$validate['error']) {
                $user = User::findOne(['username' => $validate['mobile']]);
                if ($user) {
                    Tokens::sendVerifyCode($validate['mobile'], $validate['debug']);
                }else{
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = Message::USER_NOT_FOUND;
                    $this->result->response['alert'][$this->result->index] = Alerts::USER_NOT_FOUND;
                    $this->result->index++;
                }
            }else {
                $this->result->response['error'] = true;
                $this->result->response['message'][$this->result->index] = Message::WRONG_MOBILE;
                $this->result->response['alert'][$this->result->index] = Alerts::WRONG_MOBILE;
                $this->result->index++;
            }
        }else{
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'شماره موبایل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** mobile **';
            $this->result->index++;
        }

        return $this->result->response;
    }
}