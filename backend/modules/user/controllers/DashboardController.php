<?php

namespace backend\modules\user\controllers;

use common\components\Jdf;
use common\components\Tools;
use common\models\Comments;
use common\models\Faq;
use common\models\Hints;
use common\models\Packages;
use common\models\User;
use common\modules\blog\models\Articles;
use common\modules\blog\models\Gallery;
use common\modules\main\models\Category;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class DashboardController extends ActiveController
{
    public $modelClass = 'common\models\User';
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
            'only' => ['home'],
            'rules' => [
                [
                    'actions' => ['home'],
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

    // صفحه اصلی اپلیکیشن شاگرد
    public function actionHome()
    {
        $this->result->response['data']['process'] = Gallery::find()->where(['parent_id' => 0, 'belong' => Gallery::BELONG_HOME, 'type' => Gallery::TYPE_COMPARE])
            ->limit(4)->asArray()->all();
        $this->result->response['data']['comments'] = Comments::find()->orderBy(new Expression('rand()'))->asArray()->all();
        $this->result->response['data']['faq'] = Faq::find()->where(['belong' => Faq::BELONG_HOME])->orderBy(['sort' => SORT_ASC])->asArray()->all();
        $this->result->response['data']['hints'] = Hints::find()->orderBy(['id' => SORT_DESC])->limit(5)->asArray()->all();
        $this->result->response['data']['package'] = Packages::find()->where(['status' => Packages::STATUS_READY])
            ->andWhere(['>', 'end_register', Jdf::jmktime()])->asArray()->all();

        return $this->result->response;
    }
}