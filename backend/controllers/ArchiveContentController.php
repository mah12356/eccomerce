<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\ArchiveContent;
use common\models\ArchiveContentSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * ArchiveContentController implements the CRUD actions for ArchiveContent model.
 */
class ArchiveContentController extends Controller
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
     * Lists all ArchiveContent models.
     *
     * @param int $archive_id
     * @return string
     */
    public function actionIndex(int $archive): string
    {
        $searchModel = new ArchiveContentSearch($archive);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'archive' => $archive,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView(int $id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ArchiveContent model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate(int $archive)
    {
        $model = new ArchiveContent();

        $model->archive_id = $archive;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $file = UploadedFile::getInstance($model, 'uploadFile');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, ArchiveContent::UPLOAD_FILE, $model->archive_id . '_ARCHIVE_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->file = $upload['path'];
                        }else {
                            $model->addError($file, Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }else {
                        $model->addError($file, Message::FILE_NOT_SENT);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                    if ($model->validate() && $model->save()) {
                        return $this->redirect(['index', 'archive' => $model->archive_id]);
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
     * Updates an existing ArchiveContent model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            try {
                $file = UploadedFile::getInstance($model, 'uploadFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, ArchiveContent::UPLOAD_FILE, $model->archive_id . '_ARCHIVE_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->file, ArchiveContent::UPLOAD_FILE);
                        $model->file = $upload['path'];
                    }else {
                        $model->addError($file, Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                if ($model->validate() && $model->save()) {
                    return $this->redirect(['index', 'archive' => $model->archive_id]);
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
     * Deletes an existing ArchiveContent model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Gadget::deleteFile($model->file, ArchiveContent::UPLOAD_FILE);
        }

        return $this->redirect(['index', 'archive' => $model->archive_id]);
    }

    /**
     * Finds the ArchiveContent model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ArchiveContent the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ArchiveContent::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
