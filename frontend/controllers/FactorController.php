<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\IranKish;
use common\components\Jdf;
use common\components\Navigator;
use common\components\Stack;
use common\models\Course;
use common\models\Factor;
use common\models\Packages;
use common\models\Register;
use common\modules\main\models\Config;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * FactorController implements the CRUD actions for Factor model.
 */
class FactorController extends Controller
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
     * صدور فاکتور جهت خرید پکیج
     *
     * @param int $id
     * @return Response|\yii\console\Response
     */
    public function actionBuyPackage()
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }
        
        if ($this->request->isPost) {
            if (!isset($_POST['package_id']) || !$_POST['package_id'] || !isset($_POST['courses']) || !$_POST['courses']) {
                return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
            }
            $package = Packages::find()->where(['id' => $_POST['package_id'], 'status' => Packages::STATUS_READY])->asArray()->one();
            if (!$package) {
                \Yii::$app->session->setFlash('danger', 'پکیج مورد نظر شما یافت نشد');
                return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
            }

            $requiredCourse = Course::find()->where(['package_id' => $_POST['package_id'], 'required' => Course::REQUIRED_TRUE])
                ->asArray()->all();
            $optionalCourse = Course::find()->where(['required' => Course::REQUIRED_FALSE])->andWhere(['IN', 'id', $_POST['courses']])
                ->asArray()->all();


            $courses = array_merge($requiredCourse, $optionalCourse);
            if (!$courses) {
                \Yii::$app->session->setFlash('danger', 'هیچ دوره ای برای این پکیج انتخاب نشده است');
                return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
            }

            $model = new Register();

            $response = $model->enroll(\Yii::$app->user->id, $_POST['package_id'], $courses);
            if (!$response['error']) {
                return $this->redirect(['view', 'id' => $response['data']['factor_id']]);
            } else {
                \Yii::$app->session->setFlash('danger', $response['message']);
                return $this->redirect(['index']);
            }
        }else {
            return $this->redirect(['/site/packages']);
        }
    }

    /**
     * ثبت فاکتور در درگاه برای شروع پرداخت
     *
     * @param int $id
     * @return Response|\yii\console\Response
     */
    public function actionPayFactor(int $id)
    {
//        iran-kish portal
//        $model = Factor::findOne(['id' => $id]);
//        if (!$model) {
//            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
//        }
//
//        $iranKish = new IranKish();
//        $response = $iranKish->tokenGenerator($model->amount * 10);
//        if (!$response) {
//            \Yii::$app->session->setFlash('danger', 'خطا در ایجاد ارتباط با درگاه بانکی لطفا بعدا امتحان کنید');
//            return $this->redirect(['/factor/index']);
//        }
//
//        $model->response_key = $iranKish->token;
//        if (!$model->save()) {
//            \Yii::$app->session->setFlash('danger', 'مشکل در ذخیره اطلاعلاعات فاکتور');
//            return $this->redirect(['/factor/index']);
//        }
//
//        return $this->redirect(['iran-kish-header', 'token' => $iranKish->token]);

//        zarinpal portal
        $model = Factor::findOne(['id' => $id]);
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $MerchantID = Config::getKeyContent(Config::KEY_ZARINPAL);
        $Amount = (int)$model->amount;
        $Description = 'پرداخت فاکتور : = ' . $model->id; // Required
        $Mobile = \Yii::$app->user->identity->mobile; // Optional
        $CallbackURL = Url::base('http') . '/factor/payment-result'; // Required
        $send = new \SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $send->PaymentRequest(
            [
                'MerchantID' => $MerchantID,
                'Amount' => $Amount,
                'Description' => $Description,
                'Mobile' => $Mobile,
                'CallbackURL' => $CallbackURL,
            ]
        );

        if ($result->Status == 100 || $result->Status == 101) {
            $model->response_key = $result->Authority;
            if (!$model->save()) {
                \Yii::$app->session->setFlash('danger', 'مشکل در ذخیره اطلاعلاعات فاکتور');
                return $this->redirect(['/factor/index']);
            } else {
                Header('Location: https://www.zarinpal.com/pg/StartPay/' . $result->Authority);
                exit;
            }
        } else {
            \Yii::$app->session->setFlash('danger', 'در پرداخت مشکلی پیش آمده است');
            return $this->redirect(['/factor/index']);
        }
    }

    public function actionIranKishHeader($token)
    {
        $this->render('iran-kish-header', [
            'token' => $token,
        ]);
    }

    /**
     * بررسی نتیجه پرداخت
     *
     * @return string|Response
     */
    public function actionPaymentResult()
    {
//        iran-kish portal
//        $iranKish = new IranKish();
//
//        $payment = true;
//
//        if (isset($_POST['token']) && $_POST['token'] && isset($_POST['responseCode'])) {
//            if ($_POST['responseCode'] != "00") {
//                $payment = false;
//            }
//
//            $result = $iranKish->checkResult($_POST);
//            if ($result[1]['status'] != 1) {
//                $payment = false;
//            }
//
//            $model = Factor::findOne(['response_key' => $_POST['token']]);
//            if (!$model) {
//                return $this->redirect(['index']);
//            }
//
//            $response = Register::enrollPayment($payment, $model->id);
//            if (!$response['error']) {
//                return $this->redirect(['/packages/index']);
//            } else {
//                \Yii::$app->session->setFlash('danger', $response['message'][0]);
//                return $this->redirect(['view', 'id' => $model->id]);
//            }
//        }else {
//          	\Yii::$app->session->setFlash('danger', 'نتیجه ای از درگاه بانکی دریافت نشد');
//            return $this->redirect(['/factor/index']);
//        }

//        zarinpal portal

        if (!isset($_GET['Authority'])) {
            \Yii::$app->session->setFlash('danger', 'پاسخی از طرف درگاه بانکی دریافت نشد');
            return $this->redirect(['/factor/index']);
        }

        $model = Factor::findOne(['response_key' => $_GET['Authority']]);
        if (!$model) {
            return $this->redirect(['index']);
        }

        $MerchantID = Config::getKeyContent(Config::KEY_ZARINPAL);
        $Authority = $_GET['Authority'];
        if ($_GET['Status'] != 'OK') {
            \Yii::$app->session->setFlash('danger', 'پرداخت ناموفق، در صورت کسر وجه از حساب شما نهایت تا ۷۲ ساعت کاری به حساب شما باز می‌گردد');
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $send = new \SoapClient('https://www.zarinpal.com/pg/services/WebGate/wsdl', ['encoding' => 'UTF-8']);

        $result = $send->PaymentVerification(
            [
                'MerchantID' => $MerchantID,
                'Authority' => $Authority,
                'Amount' => (int)$model->amount,
            ]
        );

        if ($result->Status == 100 || $result->Status == 101) {
            $payment = true;
        } else {
            $payment = false;
        }

        $response = Register::enrollPayment($payment, $model->id);
        if (!$response['error']) {
            return $this->redirect(['/packages/index']);
        } else {
            \Yii::$app->session->setFlash('danger', $response['message'][0]);
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }

    /**
     * لیست فاکتورهای کاربر
     *
     * @return string
     */
    public function actionIndex()
    {
        $model = Factor::find()->where(['user_id' => \Yii::$app->user->id])->with('register.package')->asArray()->all();

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * نمایش جزئیات یک فاکتور.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Factor model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     */
    public function actionDelete(int $id)
    {
        $response = Factor::deleteFactor($id);
        if ($response['error']) {
            \Yii::$app->session->setFlash('danger', $response['message'][0]);
        }
        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Factor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Factor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Factor::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
