<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Media;
use common\models\MediaSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * MediaController implements the CRUD actions for Media model.
 */
class MediaController extends Controller
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
     * Lists all Media models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MediaSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Media model.
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
     * Creates a new Media model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Media();

        $model->type = Media::TYPE_GUIDE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                try {
                    $poster = UploadedFile::getInstance($model, 'posterFile');
                    if ($poster) {
                        $upload = Gadget::yiiUploadFile($poster, Media::UPLOAD_PATH, 'POSTER__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->poster = $upload['path'];
                        }else {
                            $model->addError('posterFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
//                    else {
//                        $model->addError('posterFile', Message::FILE_NOT_SENT);
//                        return $this->render('create', [
//                            'model' => $model,
//                        ]);
//                    }

                    $file = UploadedFile::getInstance($model, 'fileInput');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Media::UPLOAD_PATH, 'FILE__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->file = $upload['path'];
                        }else {
                            $model->addError('fileInput', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
//                    else {
//                        $model->addError('fileInput', Message::FILE_NOT_SENT);
//                        return $this->render('create', [
//                            'model' => $model,
//                        ]);
//                    }


                    if ($model->save()) {
                        return $this->redirect(['index']);
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                }catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Media model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            try {
                $poster = UploadedFile::getInstance($model, 'posterFile');
                if ($poster) {
                    $upload = Gadget::yiiUploadFile($poster, Media::UPLOAD_PATH, 'POSTER__' . Jdf::jmktime());
                    if (!$upload['error']) {
                        if($model->poster != null)
                        Gadget::deleteFile($model->poster, Media::UPLOAD_PATH);
                        $model->poster = $upload['path'];
                    }else {
                        $model->addError('posterFile', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                $file = UploadedFile::getInstance($model, 'fileInput');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, Media::UPLOAD_PATH, 'FILE__' . Jdf::jmktime());
                    if (!$upload['error']) {
                        if($model->file != null)
                        Gadget::deleteFile($model->file, Media::UPLOAD_PATH);
                        $model->file = $upload['path'];
                    }else {
                        $model->addError('fileInput', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['index']);
                }else {
                    \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                }
            }catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
            }

        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Media model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Gadget::deleteFile($model->poster, Media::UPLOAD_PATH);
            Gadget::deleteFile($model->file, Media::UPLOAD_PATH);
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Media model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Media the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Media::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
