<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Regimes;
use common\models\RegimesSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RegimesController implements the CRUD actions for Regimes model.
 */
class RegimesController extends Controller
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
     * Lists all Regimes models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RegimesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Regimes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Regimes();

        $model->fit = Regimes::FIT_NONE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $file = UploadedFile::getInstance($model, 'uploadFile');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Regimes::UPLOAD_PATH, $model->calorie . '__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->file = $upload['path'];
                        }else {
                            $model->addError('uploadFile', Message::SAVE_FILE_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }else {
                        $model->addError('uploadFile', Message::FILE_NOT_SENT);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }

                    $image = UploadedFile::getInstance($model, 'uploadImage');
                    if ($image) {
                        $upload = Gadget::yiiUploadFile($image, Regimes::UPLOAD_PATH, $model->calorie . '__' . $model->fit . '__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->image = $upload['path'];
                        }else {
                            $model->addError('uploadFile', Message::SAVE_FILE_FAILED);
                            return $this->render('create', [
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
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Regimes model.
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
                $file = UploadedFile::getInstance($model, 'uploadFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, Regimes::UPLOAD_PATH, $model->calorie . '__' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->file, Regimes::UPLOAD_PATH);
                        $model->file = $upload['path'];
                    }else {
                        $model->addError('uploadFile', Message::SAVE_FILE_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                $image = UploadedFile::getInstance($model, 'uploadImage');
                if ($image) {
                    $upload = Gadget::yiiUploadFile($image, Regimes::UPLOAD_PATH, $model->calorie . '__' . $model->fit . '__' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->image, Regimes::UPLOAD_PATH);
                        $model->image = $upload['path'];
                    }else {
                        $model->addError('uploadFile', Message::SAVE_FILE_FAILED);
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

    public function actionDownloadRegime($id)
    {
        $model = $this->findModel($id);

        if (Gadget::fileExist($model->file, Regimes::UPLOAD_PATH)) {

            return \Yii::$app->response->sendFile(\Yii::getAlias('@upload') . '/' . Regimes::UPLOAD_PATH . '/' . $model->file);
        }
    }

    /**
     * Deletes an existing Regimes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $model->status = Regimes::STATUS_DELETED;

        $model->save();

//        $model->validate();
//        Gadget::preview($model->errors);

//        if ($model->save()) {
//            Gadget::deleteFile($model->file, Regimes::UPLOAD_PATH);
//            Gadget::deleteFile($model->image, Regimes::UPLOAD_PATH);
//        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Regimes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Regimes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Regimes::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
