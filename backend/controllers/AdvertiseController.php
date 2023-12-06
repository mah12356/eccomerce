<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\SendSms;
use common\models\Advertise;
use common\models\AnalyzeSearch;
use common\models\Answers;
use common\models\Chats;
use common\models\Course;
use common\models\Diet;
use common\models\Packages;
use common\models\Register;
use common\models\Tickets;
use common\models\User;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AnalyzeController implements the CRUD actions for Analyze model.
 */
class AdvertiseController extends Controller
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
    public function actionIndex()
    {
        $count = [count(Advertise::findAll(['status'=>Advertise::STATUS_SENT])),count(Advertise::findAll(['status'=>Advertise::STATUS_NOT_SEND]))];

        return $this->render('index',['count'=>$count]);
    }
    public function actionSend_with_file()
    {
        $model = new Advertise();
        if ($_FILES)
        {
            $result = Gadget::yiiUploadFile(UploadedFile::getInstance($model,'file'));
            if (!$result['error'])
            {
                $file = fopen(\Yii::getAlias("@upload")."/".$result['path'],'r');
//                Gadget:/:preview();

                while(!feof($file)) {
                    $model = new Advertise();
                    $model->number = fgets($file);
                    $model->massage = $_POST['Advertise']['massage'];
                    $model->status = Advertise::STATUS_NOT_SEND;
                    $model->save(false);
                }
                \Yii::$app->session->setFlash('success','افزودن شماره ها و پیام با موفقیت انجام شد');

                fclose($file);
            }
        }
        return $this->render('send_with_file',['model'=>$model]);
    }
    public function actionSend_with_package()
    {
        $packages = Packages::find()->orderBy(['id' => SORT_DESC])->asArray()->all();
        $model = new Advertise();
        if ($this->request->isPost)
        {
            $post = $this->request->post('Advertise');
            $users = Register::find()->where(['package_id'=>$post['package_id'],'payment'=>Register::PAYMENT_ACCEPT])
                ->with('user')->asArray()->all();
            if ($users)
            {
                foreach ($users as $user)
                {
                    $model = new Advertise();
                    $model->number = $user['user']['username'];
                    $model->massage =$post['massage'];
                    $model->status = Advertise::STATUS_NOT_SEND;
                    $model->save(false);

                }
                \Yii::$app->session->setFlash('success','افزودن شماره ها و پیام با موفقیت انجام شد');

            }else
            {
                \Yii::$app->session->setFlash('danger',"در این دوره شماره ای جهت افزودن وجود ندارد");

            }
//            Gadget::preview($users);
        }
        return $this->render('send_with_package', [
            'packages' => $packages,
            'model'=>$model
        ]);
    }

    public function actionSend_sms(){
        $model = Advertise::find()
            ->where(['status'=>Advertise::STATUS_NOT_SEND])->all();
        if($model)
        {
            $sent = 50;
            foreach ($model as $sms)
            {
                $result = SendSms::SMS($sms->number, $sms->massage);
                if ($result == true || $result == 1)
                {
                    $sms->status = Advertise::STATUS_SENT;
                    $sms->save(false);
                    --$sent;
                }else
                {
                    \Yii::$app->session->setFlash("success",'تعداد '.$sent.' پیام ها با موفقیت ارسال شدند');

                    break;
                }
                if ($sent == 0)
                {
                    \Yii::$app->session->setFlash("success",'تمامی پیام ها با موفقیت ارسال شدند');
                    break;

                }
            }
        }else
        {
            \Yii::$app->session->setFlash("danger",'پیامی جهت ارسال ثبت نشده است');
        }
        return $this->redirect(['index']);

    }
    public function actionDelete_list()
    {
        $model = Advertise::findAll(['status'=>Advertise::STATUS_NOT_SEND]);
        if ($model)
        {
            Advertise::deleteAll();
            \Yii::$app->session->setFlash("success",'حذف تمامی پیام های موجود در لیست ارسال با موفقیت انجام شد');


        }else
        {
            \Yii::$app->session->setFlash("danger",'پیامی جهت حذف ثبت نشده است');

        }
        return $this->redirect(['index']);

    }



}