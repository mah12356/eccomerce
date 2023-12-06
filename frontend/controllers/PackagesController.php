<?php

namespace frontend\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;use common\components\Message;
use common\components\Navigator;
use common\components\Stack;
use common\models\Community;
use common\models\Course;
use common\models\CourseSections;
use common\models\Diet;
use common\models\Log;
use common\models\Register;
use common\models\RegisterCourses;
use common\models\SectionContents;
use common\models\Sections;
use common\models\Packages;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * PackagesController implements the CRUD actions for Packages model.
 */
class PackagesController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        if (!isset($_SESSION['pages']) || !$_SESSION['pages']) {
            $_SESSION['pages'] = new Stack();

            $_SESSION['pages']->push(['/site/index', []]);
        }

        (new Navigator())->setUrl($_SESSION['pages'], Url::current());

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }else {
            $this->layout = 'panel';
        }

        return parent::beforeAction($action);
    }

    /**
     * پکیج های من
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $this->view->title = 'پکیج های من';

        $model = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->with('package')->orderBy(['id' => SORT_DESC])->asArray()->all();

        $packages = array();

        foreach ($model as $item) {
            if ($item['package'] && $item['package']['status'] != Packages::STATUS_INACTIVE) {
                array_push($packages, $item['package']);
            }
        }

        return $this->render('index', [
            'model' => $packages,
        ]);
    }

    /**
     * نمایش جلسات یک دوره
     *
     * @param int $id
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionCourseSections(int $id)
    {
        $course = Course::find()->where(['id'=>  $id])->asArray()->one();
        if (!$course) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $this->view->title = 'لیست جلسات ' . $course['name'];

        $courseSections = CourseSections::find()->where(['course_id' => $id])->asArray()->all();

        $sections = Sections::find()->where(['group' => ArrayHelper::map($courseSections, 'id', 'section_group')])
            ->orderBy(['date' => SORT_ASC])->asArray()->all();

        return $this->render('course-sections', [
            'model' => $sections,
        ]);
    }
    public function actionHedieh(){
        return $this->render('hedieh');
    }
    /**
     * نمایش محتوای یک جلسه
     *
     * @param int $id
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionSectionContent(int $id)
    {
        $model = Sections::find()->where(['id' => $id])->with('content')->asArray()->one();
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }
        if ($model['status'] != Sections::STATUS_COMPLETE || $model['mood'] != Sections::MOOD_COMPLETE) {
            \Yii::$app->session->setFlash('danger', 'محتوای جلسه بارگذاری نشده است');
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }
        
        return $this->render('section-content', [
            'model' => $model,
        ]);
    }

    public function actionLiveStream(int $id)
    {
        $model = Sections::find()->where(['id' => $id])->asArray()->one();
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

//        if (!\Yii::$app->user->isGuest && \Yii::$app->user->id) {
//            Log::inset(Log::PAGE_TYPE_LIVE, $id, \Yii::$app->user->id);
//        }

        $comments = new Community();

        return $this->render('live-stream', [
            'model' => $model,
            'comments' => $comments,
        ]);
    }

    public function actionAllSections()
    {
        $this->view->title = 'پخش زنده';

        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($packages, 'id', 'id')])->asArray()->all();
        $registerCourses = RegisterCourses::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->asArray()->all();
        $courseSections = CourseSections::find()->where(['IN', 'course_id', ArrayHelper::map($registerCourses, 'course_id', 'course_id')])->asArray()->all();


        $placed = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
            ->andWhere(['status' => Sections::STATUS_COMPLETE, 'mood' => Sections::MOOD_COMPLETE])
            ->orderBy(['date' => SORT_DESC])->asArray()->all();
        $live = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
            ->andWhere(['type' => Sections::TYPE_LIVE])->andWhere(['!=', 'mood', Sections::MOOD_COMPLETE])
            ->andWhere(['date' => Jdf::jmktime(0, 0, 0, Jdf::jdate('m', Jdf::jmktime()), Jdf::jdate('d', Jdf::jmktime()), Jdf::jdate('Y', Jdf::jmktime()))])
            ->orderBy(['date' => SORT_DESC])->asArray()->all();

//        Gadget::preview($placed);
//        Gadget::preview($live);

//        $sections = Sections::find()->where(['IN', 'group', ArrayHelper::map($courseSections, 'section_group', 'section_group')])
//            ->andWhere(['status' => Sections::STATUS_COMPLETE, 'mood' => Sections::MOOD_COMPLETE])->orderBy(['date' => SORT_DESC])
//            ->asArray()->all();

        return $this->render('all-sections', [
            'live' => $live,
            'placed' => $placed,
            'new' =>$register
        ]);
    }

    public function actionRoadMap()
    {
        $this->layout = 'main';
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->all();
        $registerCourse = RegisterCourses::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->asArray()->all();
        $course = Course::find()->where(['NOT IN', 'belong', [Course::BELONG_ANALYZE, Course::BELONG_DIET]])
            ->andWhere(['IN', 'id', ArrayHelper::map($registerCourse, 'course_id', 'course_id')])->asArray()->all();

        return $this->render('road-map', [
            'course' => $course,
        ]);
    }

    public function actionFindCourseSections($id)
    {
        $course = Course::findOne(['id' => $id]);
        if (!$course) {
            return '';
        }

        $content = CourseSections::find()->where(['course_id' => $id])->asArray()->all();
        $sections = Sections::find()->where(['IN', 'group', ArrayHelper::map($content, 'section_group', 'section_group')])
            ->asArray()->all();

        $currentDay = Jdf::jmktime(0, 0, 0, Jdf::jdate('m', Jdf::jmktime()), Jdf::jdate('d', Jdf::jmktime()), Jdf::jdate('Y', Jdf::jmktime()));

        $response = '';
        for ($i = 0; $i < count($sections); $i += 2) {
            if ($currentDay > $sections[$i]['date']) {
                $class = 'step passed';
            }elseif ($currentDay == $sections[$i]['date']) {
                $class = 'step current';
            }else {
                $class = 'step remain';
            }
            $response .= '<div class="progress-container">';
            $response .= '<div class="progress"></div>';

            $response .= '<div class="'. $class .'">';
            $response .= '<p class="text-center"><span class="badge bg-danger">'. Gadget::convertToPersian($i+1) .'</span></p>';
            $response .= '<p class="section-title">'. $sections[$i]['title'] .'</p>';
            $response .= '<div class="section-date">'. Jdf::jdate('Y/m/d', $sections[$i]['date']) .'</div>';
            $response .= '</div>';

            if (isset($sections[$i+1]) && $sections[$i+1]['title']) {
                if ($currentDay > $sections[$i+1]['date']) {
                    $class = 'step passed';
                }elseif ($currentDay == $sections[$i+1]['date']) {
                    $class = 'step current';
                }else {
                    $class = 'step remain';
                }
                $response .= '<div class="'. $class .'">';
                $response .= '<p class="text-center"><span class="badge bg-danger">'. Gadget::convertToPersian($i+2) .'</span></p>';
                $response .= '<p class="section-title">'. $sections[$i+1]['title'] .'</p>';
                $response .= '<div class="section-date">'. Jdf::jdate('Y/m/d', $sections[$i+1]['date']) .'</div>';
                $response .= '</div>';
            }

            $response .= '</div>';
        }

        return $response;
    }

    public function actionSendComment(): string
    {
        if (!isset($_POST['id']) || !$_POST['id']) {
            return 'خطا در ثبت پیام';
        }
        if (!isset($_POST['text']) || !$_POST['text']) {
            return 'متن پیام نمی تواند خالی باشد';
        }

        $model = new Community();

        $model->user_id = \Yii::$app->user->id;
        $model->parent_id = $_POST['id'];
        $model->belong = Community::BELONG_LIVE;
        $model->text = $_POST['text'];
        $model->date = Jdf::jmktime();
        $model->status = Community::STATUS_SUBMIT;

        if ($model->save()) {
            return '';
        }else {
            return 'خطا در ارسال پیام';
        }
    }

    /**
     * نمایش دوره های خریداری شده یک پکیج
     *
     * @param int $package_id
     * @return mixed
     */
    public function actionFindPackageCourses(int $package_id)
    {
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id,'package_id' => $package_id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->one();
        if (!$register) {
            return '';
        }

        $registerCourses = RegisterCourses::find()->where(['register_id' => $register['id']])->asArray()->all();
        if (!$registerCourses) {
            return '';
        }

        $courses = Course::find()->where(['IN', 'id', ArrayHelper::map($registerCourses, 'id', 'course_id')])
            ->andWhere(['IN', 'belong', [Course::BELONG_LIVE, Course::BELONG_OFFLINE]])->asArray()->all();

        $url = \Yii::$app->urlManager;
        $content = '<div class="row">';

        foreach ($courses as $item) {
            $content .= '<div class="col-md-4 mb-4">';
            $content .= '<div class="course-card">';
            $content .= '<div class="header">';
            $content .= '<img src="'. Gadget::showFile($item['poster'], Course::UPLOAD_PATH) .'" alt="'. $item['alt'] .'">';
            $content .= '</div>';
            $content .= '<div class="body">';
            $content .= '<p class="name">'. $item['name'] .'</p>';
            $content .= '<p class="intro">'. $item['description'] .'</p>';
            $content .= '<a class="register" href="'. $url->createUrl(['/packages/course-sections', 'id' => $item['id']]) .'">لیست جلسات</a>';
            $content .= '</div>';
            $content .= '</div>';
            $content .= '</div>';
        }

        $content .= '</div>';

        return $content;
    }
}
