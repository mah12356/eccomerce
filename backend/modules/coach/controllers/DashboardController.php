<?php

namespace backend\modules\coach\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Tools;
use common\models\Course;
use common\models\CourseSections;
use common\models\Packages;
use common\models\Sections;
use common\models\User;
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
            'only' => ['home', 'sections'],
            'rules' => [
                [
                    'actions' => ['home', 'sections'],
                    'allow' => true,
                    'roles' => [User::ROLE_COACH],
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
        Sections::readySection(\Yii::$app->user->id);

        $current = Jdf::jmktime();
        $today = Jdf::jmktime(0, 0, 0, Gadget::convertToEnglish(Jdf::jdate('m', $current)), Gadget::convertToEnglish(Jdf::jdate('d', $current)), Gadget::convertToEnglish(Jdf::jdate('Y', $current)));

        $packages = Packages::find()->where(['coach_id' => \Yii::$app->user->id, 'status' => Packages::STATUS_READY])->asArray()->all();
        $onlineCourses = Course::find()->where(['IN', 'package_id', ArrayHelper::map($packages, 'id', 'id')])
            ->andWhere(['belong' => Course::BELONG_LIVE])->asArray()->all();

        $coursesSections = CourseSections::find()->where(['IN', 'course_id', ArrayHelper::map($onlineCourses, 'id', 'id')])
            ->asArray()->all();

        $this->result->response['data']['readySections'] = Sections::find()->where(['IN', 'group', ArrayHelper::map($coursesSections, 'section_group', 'section_group')])
            ->andWhere(['type' => Sections::TYPE_LIVE, 'date' => $today, 'status' => Sections::STATUS_PROCESS, 'mood' => [Sections::MOOD_READY, Sections::MOOD_PLAYING]])
            ->asArray()->all();

        return $this->result->response;
    }
    // لیست جلسات مربی
    public function actionSections()
    {
        $this->result->response['data'] = Packages::find()->where(['coach_id' => \Yii::$app->user->id, 'status' => Packages::STATUS_READY])
            ->with('courses.sections')->asArray()->all();

        return $this->result;
    }
    // لیست شاگردان
    public function actionStudents()
    {
        return $this->result->response['packages'] = Packages::find()->where(['coach_id' => \Yii::$app->user->id, 'status' => Packages::STATUS_READY])
            ->with('registers.user')->asArray()->all();
    }
}