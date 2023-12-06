<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Navigator;
use common\components\Stack;
use common\models\Analyze;
use common\models\Channels;
use common\models\Chats;
use common\models\Diet;
use common\models\Hints;
use common\models\Packages;
use common\models\Register;
use common\models\Tickets;
use common\models\TicketsSearch;
use common\modules\main\models\Config;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * TicketsController implements the CRUD actions for Tickets model.
 */
class TicketsController extends Controller
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
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex()
    {
        Tickets::defineTickets(\Yii::$app->user->id);

        $model = Tickets::find()->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['NOT IN', 'type', [Tickets::TYPE_DEVELOPER, Tickets::TYPE_SUPPORT]])->asArray()->all();

        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->all();

        $channels = Channels::find()->where(['IN', 'package_id', ArrayHelper::map($register, 'package_id', 'package_id')])
            ->asArray()->all();

        return $this->render('index', [
            'model' => $model,
            'channels' => $channels,
        ]);
    }

    public function actionChats($id)
    {
        $ticket = Tickets::find()->where(['id' => $id])->with('chats')->asArray()->one();
        if (!$ticket) {
            return $this->redirect(['index']);
        }
        if(isset($_GET['type']) && $_GET['type']==Diet::TYPE_DIET && isset($_GET['check']) && $_GET['check'] == 'check' ){
            $idDiet = Tickets::find()->where(['user_id' => $ticket['user_id'],'type'=>Tickets::TYPE_DIET])->one();
            $idSupport = Tickets::find()->where(['user_id' => $ticket['user_id'],'type'=>Tickets::TYPE_SUPPORT])->one();
            $_GET = null;
            return $this->render('question',['idDiet'=>$idDiet , 'idSupport'=>$idSupport ]);
        }

        Tickets::statusManager($id, 'client');

        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($packages, 'id', 'id')])->asArray()->all();

        $hint = '';
        $chatBox = true;

        switch ($ticket['type']) {
            case Tickets::TYPE_DIET:
                $hint = Hints::find()->where(['belong' => Hints::BELONG_DIET_TICKET])->orderBy(['id' => SORT_DESC])->asArray()->one();
                $access = Config::getKeyContent(Config::KEY_DIET_TICKET);
                if (!$access) {
                    $chatBox = false;
                    break;
                }
                $diet = Diet::find()->where(['!=', 'status', Diet::STATUS_PENDING])->andWhere(['user_id' => \Yii::$app->user->id])
                    ->andWhere(['IN', 'package_id', ArrayHelper::map($register, 'package_id', 'package_id')])->asArray()->one();

                if (!$diet) {
                    $chatBox = false;
                    break;
                }
                break;
            case Tickets::TYPE_ANALYZE:
                $access = Config::getKeyContent(Config::KEY_ANALYZE_TICKET);
                if (!$access) {
                    $chatBox = false;
                    break;
                }
                $analyze = Analyze::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
                    ->andWhere(['user_id' => \Yii::$app->user->id])->andWhere(['!=', 'status', Analyze::STATUS_INACTIVE])->asArray()->all();
                if (!$analyze) {
                    $chatBox = false;
                    break;
                }
                break;
            default:
                $chatBox = true;
                break;
        }

        $model = new Chats();

        $model->parent_id = $id;
        $model->belong = Chats::BELONG_TICKET;
        $model->sender = Chats::SENDER_CLIENT;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $image = UploadedFile::getInstance($model, 'imageFile');
                    $audio = UploadedFile::getInstance($model, 'audioFile');
                    $video = UploadedFile::getInstance($model, 'videoFile');
                    $document = UploadedFile::getInstance($model, 'documentFile');

                    if (!$model->text && !$image && !$audio && !$video && !$document) {
                        return $this->render('chats', [
                            'model' => $model,
                            'ticket' => $ticket,
                            'hint' => $hint,
                            'chatBox' => $chatBox,
                        ]);
                    }

                    if ($image) {
                        $upload = Gadget::yiiUploadFile($image, Chats::UPLOAD_PATH, $model->parent_id . '__IMAGE__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->image = $upload['path'];
                        } else {
                            $model->addError('imageFile', 'خطا در بارگذاری');
                            return $this->render('chats', [
                                'model' => $model,
                                'ticket' => $ticket,
                                'hint' => $hint,
                                'chatBox' => $chatBox,
                            ]);
                        }
                    }
                    if ($audio) {
                        $upload = Gadget::yiiUploadFile($audio, Chats::UPLOAD_PATH, $model->parent_id . '__AUDIO__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->audio = $upload['path'];
                        } else {
                            $model->addError('audioFile', 'خطا در بارگذاری');
                            return $this->render('chats', [
                                'model' => $model,
                                'ticket' => $ticket,
                                'hint' => $hint,
                                'chatBox' => $chatBox,
                            ]);
                        }
                    }
                    if ($video) {
                        $upload = Gadget::yiiUploadFile($video, Chats::UPLOAD_PATH, $model->parent_id . '__VIDEO__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->video = $upload['path'];
                        } else {
                            $model->addError('videoFile', 'خطا در بارگذاری');
                            return $this->render('chats', [
                                'model' => $model,
                                'ticket' => $ticket,
                                'hint' => $hint,
                                'chatBox' => $chatBox,
                            ]);
                        }
                    }
                    if ($document) {
                        $upload = Gadget::yiiUploadFile($document, Chats::UPLOAD_PATH, $model->parent_id . '__DOCUMENT__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->document = $upload['path'];
                        } else {
                            $model->addError('documentFile', 'خطا در بارگذاری');
                            return $this->render('chats', [
                                'model' => $model,
                                'ticket' => $ticket,
                                'hint' => $hint,
                                'chatBox' => $chatBox,
                            ]);
                        }
                    }

                    if ($model->saveMessage()) {
                        return $this->redirect(['chats', 'id' => $id]);
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                }
            }
        }

        return $this->render('chats', [
            'model' => $model,
            'ticket' => $ticket,
            'hint' => $hint,
            'chatBox' => $chatBox,
        ]);
    }

    public function actionChannel($id)
    {
        $channel = Channels::find()->where(['id' => $id])->with('chats')->asArray()->one();
        if (!$channel) {
            return $this->redirect(['index']);
        }

        return $this->render('channel', [
            'channel' => $channel,
        ]);
    }

    public function actionDownloadContent($content)
    {
        if (Gadget::fileExist($content, Chats::UPLOAD_PATH)) {

            return \Yii::$app->response->sendFile(\Yii::getAlias('@upload') . '/' . Chats::UPLOAD_PATH . '/' . $content);
        }
    }
}
