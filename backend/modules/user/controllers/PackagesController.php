<?php

namespace backend\modules\user\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use common\models\Course;
use common\models\CourseSections;
use common\models\Register;
use common\models\RegisterCourses;
use common\models\Sections;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class PackagesController extends ActiveController
{
    public $modelClass = 'common\models\Packages';
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
            'only' => ['list', 'course-sections', 'section-content', 'all-sections'],
            'rules' => [
                [
                    'actions' => ['list', 'course-sections', 'section-content', 'all-sections'],
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
    // لیست پکیج های
    public function actionList()
    {
        $this->result->response['data'] = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->with('package.courses')->asArray()->all();

        return $this->result->response;
    }
    // نمایش جلسات یک دوره
    public function actionCourseSections()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = 'شناسه دوره' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . '** course id **';
            $this->result->index++;
            return $this->result->response;
        }

        $course = Course::find()->where(['id' => $post['id']])->with('sections')->asArray()->one();
        if (!$course) {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = 'دوره مورد نظر یافت نشد';
            $this->result->response['alert'][$this->result->index] = 'course not found';
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->respones['data'] = $course;
        return $this->result->respones;
    }
    // نمایش محتوای یک جلسه
    public function actionSectionContent()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = 'شناسه جلسه' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . '** section id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Sections::findOne(['id' => $post['id']]);
        if (!$model) {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'][$this->result->index] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }
        if ($model->status != Sections::STATUS_COMPLETE || $model->mood != Sections::MOOD_COMPLETE) {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = 'محتوای جلسه بارگذاری نشده است';
            $this->result->response['alert'][$this->result->index] = 'FILE NOT SAVE';
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->response['data'] = $model->file;

        return $this->result->response;
    }
    //
    public function actionAllSections()
    {
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])->asArray()->all();
        $registerCourses = RegisterCourses::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->with('course.contains.held')->asArray()->all();

        $courseSections = CourseSections::find()->where(['IN', 'course_id', ArrayHelper::map($registerCourses, 'course_id', 'course_id')])->asArray()->all();

        $live = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
            ->andWhere(['type' => Sections::TYPE_LIVE, 'status' => Sections::STATUS_PROCESS, 'mood' => Sections::MOOD_PLAYING])
            ->orderBy(['date' => SORT_ASC])->asArray()->all();

//        $sections = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
//            ->andWhere(['status' => Sections::STATUS_COMPLETE, 'mood' => Sections::MOOD_COMPLETE])->orderBy(['date' => SORT_DESC])
//            ->asArray()->all();

        $this->result->response['data']['live'] = $live;
        $this->result->response['data']['sections'] = $registerCourses;

        return $this->result->response;
    }
}