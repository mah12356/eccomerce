<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Channels;
use common\models\ChannelsSearch;
use common\models\Chats;
use common\models\Packages;
use common\models\Tickets;
use common\models\User;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ChannelsController implements the CRUD actions for Channels model.
 */
class ChannelsController extends Controller
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
     * Lists all Channels models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ChannelsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Channels model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Channels();

        $model->coach_id = 5;

        $packages = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $file = UploadedFile::getInstance($model, 'file');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Channels::UPLOAD_PATH, $model->coach_id . '_' . $model->package_id . '_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->avatar = $upload['path'];
                        } else {
                            $model->addError('file', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                                'packages' => $packages,
                            ]);
                        }
                    }

                    if ($model->save()) {
                        return $this->redirect(['index']);
                    } else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'packages' => $packages,
        ]);
    }

    /**
     * Updates an existing Channels model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        $packages = Packages::find()->where(['coach_id' => 5, 'status' => Packages::STATUS_READY])->asArray()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $file = UploadedFile::getInstance($model, 'file');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Channels::UPLOAD_PATH, $model->coach_id . '_' . $model->package_id . '_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            Gadget::deleteFile($model->avatar, Chats::UPLOAD_PATH);
                            $model->avatar = $upload['path'];
                        } else {
                            $model->addError('file', Message::UPLOAD_FAILED);
                            return $this->render('update', [
                                'model' => $model,
                                'packages' => $packages,
                            ]);
                        }
                    }

                    if ($model->save()) {
                        return $this->redirect(['index']);
                    } else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('update', [
            'model' => $model,
            'packages' => $packages,
        ]);
    }

    /**
     * Deletes an existing Channels model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $transaction = \Yii::$app->db->beginTransaction();
        $model = $this->findModel($id);

        if ($model->delete()) {
            if (Chats::findAll(['parent_id' => $id, 'belong' => Chats::BELONG_CHANNEL])) {
                if (!Chats::deleteAll(['parent_id' => $id, 'belong' => Chats::BELONG_CHANNEL])) {
                    $transaction->rollBack();
                    return $this->redirect(['index']);
                }
            }
            $transaction->commit();
        }

        return $this->redirect(['index']);
    }

    public function actionChats($id)
    {
        $channel = Channels::find()->where(['id' => $id])->with('chats')->asArray()->one();
        if (!$channel) {
            return $this->redirect(['index']);
        }

        $this->view->title = $channel['name'];

        $model = new Chats();

        $model->parent_id = $id;
        $model->belong = Chats::BELONG_CHANNEL;
        $model->sender = Chats::SENDER_ADMIN;

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
                            'channel' => $channel,
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
                                'channel' => $channel,
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
                                'channel' => $channel,
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
                                'channel' => $channel,
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
                                'channel' => $channel,
                            ]);
                        }
                    }

                    $model->date = Jdf::jmktime();

                    if ($model->save()) {
                        return $this->redirect(['chats', 'id' => $id]);
                    } else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                }
            }
        }

        return $this->render('chats', [
            'model' => $model,
            'channel' => $channel,
        ]);
    }

    public function actionDeleteChat($id)
    {
        $model = Chats::findOne(['id' => $id]);
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $model->delete();
        return $this->redirect(['chats', 'id' => $model['parent_id']]);
    }

    public function actionDownloadContent($content)
    {
        if (Gadget::fileExist($content, Chats::UPLOAD_PATH)) {

            return \Yii::$app->response->sendFile(\Yii::getAlias('@upload') . '/' . Chats::UPLOAD_PATH . '/' . $content);
        }
    }

    /**
     * Finds the Channels model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Channels the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected
    function findModel($id)
    {
        if (($model = Channels::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
