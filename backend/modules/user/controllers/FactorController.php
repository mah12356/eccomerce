<?php

namespace backend\modules\user\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use common\models\Factor;
use common\models\Register;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;

class FactorController extends ActiveController
{
    public $modelClass = 'common\models\Factor';
    public object $result;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
            'except' => [],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['buy-package', 'list'],
            'rules' => [
                [
                    'actions' => ['buy-package', 'list'],
                    'allow' => true,
                    'roles' => [User::ROLE_USER],
                ],
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->result = new Tools();
        return parent::beforeAction($action);
    }

    // ثبت فاکتور برای ثبت نام در پکیج
    public function actionBuyPackage()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه پکیح' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** package id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = new Register();

        $response = $model->enroll(\Yii::$app->user->id, $post['id']);
        if (!$response['error']) {
            $this->result->response['data']['factor_id'] = $response['data']['factor_id'];
        }else {
            $this->result->response['error'] = true;
            $this->result->response['message'] = $response['message'];
            $this->result->response['alert'] = $response['alert'];
            $this->result->index++;
        }

        return $this->result->response;
    }
    // نمایش لیست فاکتور های کاربر
    public function actionList()
    {
        $this->result->response['data'] = Factor::find()->where(['user_id' => \Yii::$app->user->id])
            ->with('register.package')->asArray()->all();

        return $this->result->response;
    }
    // نمایش جزئیات یک فاکتور
    public function actionDetail()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه فاکتور' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** factor id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Factor::find()->where(['id' => $post['id']])->asArray()->all();
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'فاکتور مورد نظر شما یافت نشد';
            $this->result->response['alert'] = 'factor not found';
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->response['data'] = $model;
        return $this->result->response;
    }
    // حذف یک فاکتور
    public function actionRemove()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه فاکتور' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** factor id **';
            $this->result->index++;
            return $this->result->response;
        }

        return Factor::deleteFactor($post['id']);
    }
}