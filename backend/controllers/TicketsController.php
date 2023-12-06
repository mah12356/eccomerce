<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Answers;
use common\models\Chats;
use common\models\Course;
use common\models\Diet;
use common\models\Packages;
use common\models\Register;
use common\models\Tickets;
use common\models\TicketsSearch;
use common\models\User;
use yii\helpers\ArrayHelper;
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

    /**
     * Lists all Tickets models.
     *
     * @return string
     */
    public function actionIndex($type, $status = 'admin message')
    {
        if ($status != 'admin message') {
            $status = ['admin checked', 'client message', 'client checked'];
        }
        $searchModel = new TicketsSearch($type, $status);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'type' => $type,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionChats($id)
    {
        $ticket = Tickets::find()->where(['id' => $id])->with('chats')->asArray()->one();
        if (!$ticket) {
            return $this->redirect(['index']);
        }

        $actives = [];

        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
        $register = Register::find()->where(['user_id' => $ticket['user_id'], 'payment' => Register::PAYMENT_ACCEPT, 'status' => Register::STATUS_PROCESSING])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($packages, 'id', 'id')])
            ->with('package')->with('courses')->asArray()->all();

        $diet = Diet::find()->where(['user_id' => $ticket['user_id']])->andWhere(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->andWhere(['IN', 'package_id', ArrayHelper::map($register, 'package_id', 'package_id')])->asArray()->one();

        foreach ($register as $item) {
            if ($item['courses']) {
                if ($diet) {
                    $actives[] = [
                        'package' => $item['package']['name'],
                        'courses' => Course::find()->where(['IN', 'id',
                            array_merge(ArrayHelper::map($item['courses'], 'course_id', 'course_id'), [0 => $diet['course_id']])])
                            ->asArray()->all(),
                    ];
                }else {
                    $actives[] = [
                        'package' => $item['package']['name'],
                        'courses' => Course::find()->where(['IN', 'id', ArrayHelper::map($item['courses'], 'course_id', 'course_id')])
                            ->asArray()->all(),
                    ];
                }
            }
        }
        
        $answers = null;

        if ($ticket['type'] == Tickets::TYPE_DIET) {
            $diet = Diet::find()->where(['user_id' => $ticket['user_id']])->andWhere(['IN', 'status', [Diet::STATUS_WAIT_FOR_RESPONSE, Diet::STATUS_REGIME_UPLOADED]])
                ->orderBy(['id' => SORT_DESC])->asArray()->one();

            if ($diet) {
                $answers = Answers::find()->where(['diet_id' => $diet['id']])
                    ->with('question')->with('options.content')->asArray()->all();
            }
        }

        $user = User::findOne(['id' => $ticket['user_id']]);
        if (!$user) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $this->view->title = $user->name . ' ' . $user->lastname;

        $model = new Chats();

        $reference = new Chats();

        $model->parent_id = $id;
        $model->belong = Chats::BELONG_TICKET;
        $model->sender = Chats::SENDER_ADMIN;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $reference->load($this->request->post())) {
                if (!isset($_POST['Chats']['referenceTo']) || !$_POST['Chats']['referenceTo']
                    || !isset($_POST['Chats']['referenceCount']) || !$_POST['Chats']['referenceCount']) {

                    try {
                        $image = UploadedFile::getInstance($model, 'imageFile');
                        $audio = UploadedFile::getInstance($model, 'audioFile');
                        $video = UploadedFile::getInstance($model, 'videoFile');
                        $document = UploadedFile::getInstance($model, 'documentFile');

                        if (!$model->text && !$image && !$audio && !$video && !$document) {
                            return $this->render('chats', [
                                'model' => $model,
                                'ticket' => $ticket,
                                'reference' => $reference,
                                'answers' => $answers,
                                'actives' => $actives,
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
                                    'reference' => $reference,
                                    'answers' => $answers,
                                    'actives' => $actives,
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
                                    'reference' => $reference,
                                    'answers' => $answers,
                                    'actives' => $actives,
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
                                    'reference' => $reference,
                                    'answers' => $answers,
                                    'actives' => $actives,
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
                                    'reference' => $reference,
                                    'answers' => $answers,
                                    'actives' => $actives,
                                ]);
                            }
                        }
//                        Gadget::preview($model);
                        if ($model->saveMessage()) {
//                            Tickets::statusManager($id, 'admin');
                            return $this->redirect(['chats', 'id' => $id]);
                        }else {
                            \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        }
                    } catch (\Exception $exception) {
                        \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                    }
                }else {
                    $response = Tickets::referenceMessage($id, $_POST['Chats']['referenceTo'], $_POST['Chats']['referenceCount']);
                    if (!$response['error']) {
                        \Yii::$app->session->setFlash('success', 'پیام ها با موفقیت ارجاع پیدا کرد');
                        return $this->redirect(['chats', 'id' => $id]);
                    }else {
                        \Yii::$app->session->setFlash('danger', $response['message']);
                        return $this->render('chats', [
                            'model' => $model,
                            'ticket' => $ticket,
                            'reference' => $reference,
                            'answers' => $answers,
                            'actives' => $actives,
                        ]);
                    }
                }
            }
        }

        return $this->render('chats', [
            'model' => $model,
            'ticket' => $ticket,
            'reference' => $reference,
            'answers' => $answers,
            'actives' => $actives,
            'user'=>$user
        ]);
    }

    public function actionDownloadContent($content)
    {
        if (Gadget::fileExist($content, Chats::UPLOAD_PATH)) {

            return \Yii::$app->response->sendFile(\Yii::getAlias('@upload') . '/' . Chats::UPLOAD_PATH . '/' . $content);
        }
    }
}
