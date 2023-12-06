<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Navigator;
use common\components\Stack;
use common\models\Answers;
use common\models\Course;
use common\models\Diet;
use common\models\Questions;
use common\models\Regimes;
use common\models\Register;
use common\models\Packages;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DietController implements the CRUD actions for Diet model.
 */
class DietController extends Controller
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
     * لیست برنامه های غذایی کاربر
     *
     * @return string
     */
    public function actionIndex()
    {
        $this->view->title = 'برنامه های غذایی';

        $model = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->with('package')->orderBy(['id' => SORT_DESC])->asArray()->all();

        $package_id = ArrayHelper::map($model, 'id', 'package_id');

        Diet::activeDiets(\Yii::$app->user->id, $package_id);

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
     * پاسخ به سوالات رژیم برای دریافت برنامه غذایی
     *
     * int $id
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionGetRegime(int $id)
    {
        $model = Diet::findOne(['id' => $id]);
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }
        if ($model->status != Diet::STATUS_WAIT_FOR_ANSWERS) {
            \Yii::$app->session->setFlash('danger', 'سوالات برنامه غذایی در حال آماده سازی است');
            return $this->redirect(['/site/dashboard']);
        }

        $course = Course::findOne(['id' => $model->course_id]);

        $this->view->title = 'دریافت برنامه غذایی دوره : ' . $course->name;

        $questions = Questions::find()->where(['course_id' => 0])->with('options')->asArray()->all();
        if ($this->request->isPost) {
            unset($_POST['_csrf-frontend']);
            $response = Answers::setResponse($_POST, \Yii::$app->user->id, $id);
            if ($response['error']) {
                \Yii::$app->session->setFlash('danger', $response['message']);
            }

            return $this->redirect(['index']);
        }

        return $this->render('get-regime', [
            'model' => $model,
            'questions' => $questions,
        ]);
    }

    public function actionFindPackageDiets($package_id)
    {
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['package_id' => $package_id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->all();
        $model = Diet::find()->where(['user_id' => \Yii::$app->user->id, 'package_id' => $package_id])
            ->andWhere(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])->asArray()->all();

        $i = 1;
        $j = 1;
        $url = \Yii::$app->urlManager;
        $content = '';

        foreach ($model as $item) {

            if ($item['type'] == Diet::TYPE_DIET) {
                $title = 'برنامه غذایی ' . $i;
                $i++;
            }else {
                $title = 'رژیم شوک ' . $j;
                $j++;
            }

            $content .= '<div class="row diet-box">';
            $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2">'. $title .'</p></div>';
            switch ($item['status']) {
                case Diet::STATUS_PENDING:
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2"><span><i class="fa fa-exclamation-circle mx-2"></i>وضعیت : </span><span>در انتظار فعالسازی</span></p></div>';
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-hourglass-half mx-2"></i>تاریخ فعالسازی : </span><span>'. Jdf::jdate('Y/m/d', $item['date']) .'</span></p></div>';
                    break;
                case Diet::STATUS_WAIT_FOR_ANSWERS:
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-exclamation-circle mx-2"></i>وضعیت : </span><span>در انتظار پاسخ به سوالات</span></p></div>';
                    $content .= '<div class="col-md-4 col-12"><a href="'. $url->createUrl(['/diet/get-regime', 'id' => $item['id']]) .'" class="diet-box-btn">پاسخ به سوالات</a></div>';
                    break;
                case Diet::STATUS_WAIT_FOR_RESPONSE:
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-exclamation-circle mx-2"></i>وضعیت : </span><span>در انتظار بارگذاری برنامه</span></p></div>';
                    $content .= '<div class="col-md-4 col-12"><a class="diet-box-btn diet-box-btn-disable">دانلود برنامه غذایی</a></div>';
                    break;
                case Diet::STATUS_REGIME_UPLOADED:
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-exclamation-circle mx-2"></i>وضعیت : </span><span>تکمیل فرآیند</span></p></div>';
                    $content .= '<div class="col-md-4 col-12"><a href="'. $url->createUrl(['/diet/download-regime', 'id' => $item['regime_id']]) .'" class="diet-box-btn">دانلود برنامه غذایی</a></div>';
                    break;
                case Diet::STATUS_REGIME_NOT_FOUND:
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-exclamation-circle mx-2"></i>وضعیت : </span><span>تماس با مشاور تغذیه</span></p></div>';
                    $content .= '<div class="col-md-4 col-12"><p class="diet-box-subjects pt-2 text-center"><span><i class="fa fa-hourglass-half mx-2"></i>تاریخ پاسخ مشاور : </span><span>'. Jdf::jdate('Y/m/d', $item['date_update']) .'</span></p></div>';
                    break;
            }
            $content .= '</div>';
        }

        return $content;
    }

    public function actionDownloadRegime($id)
    {
        $model = Regimes::findOne(['id' => $id]);
        if (!$model) {
            return $this->redirect(['index']);
        }

        if (Gadget::fileExist($model->file, Regimes::UPLOAD_PATH)) {

            return \Yii::$app->response->sendFile(\Yii::getAlias('@upload') . '/' . Regimes::UPLOAD_PATH . '/' . $model->file);
        }
    }

    /**
     * Finds the Diet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Diet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diet::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
