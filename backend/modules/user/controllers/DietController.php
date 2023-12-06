<?php

namespace backend\modules\user\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use common\models\Diet;
use common\models\Questions;
use common\models\Register;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class DietController extends ActiveController
{
    public $modelClass = 'common\models\Diet';
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
            'only' => ['list', 'get-regime-question', 'get-answer'],
            'rules' => [
                [
                    'actions' => ['list', 'get-regime-question', 'get-answer'],
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

    // نمایش رژیم های غذایی یک کاربر
    public function actionList()
    {
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->all();

        $package_id = ArrayHelper::map($register, 'id', 'package_id');

        Diet::activeDiets(\Yii::$app->user->id, $package_id);

        $this->result->response['data'] = Diet::find()->where(['user_id' => \Yii::$app->user->id])->andWhere(['IN', 'package_id', $package_id])
            ->orderBy(['date_update' => SORT_DESC])->asArray()->all();

        return $this->result->response;
    }
    // دریافت سوالات برای دریافت برنامه غذایی
    public function actionGetRegimeQuestions()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه برنامه غذایی' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** diet id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Diet::findOne(['id' => $post['id']]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }
        if ($model->status != Diet::STATUS_WAIT_FOR_ANSWERS) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'سوالات برنامه غذایی در حال آماده سازی است';
            $this->result->response['alert'] = 'diet status not ready';
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->response['data'] = Questions::find()->where(['course_id' => 0])->with('options')->asArray()->all();
        return $this->result->response;
    }
    // دریافت پاسخ های کاربر
    public function actionGetAnswers()
    {
        return $this->result->response;
    }
}