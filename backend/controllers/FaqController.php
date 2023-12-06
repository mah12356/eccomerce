<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Faq;
use common\models\FaqSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * FaqController implements the CRUD actions for Faq model.
 */
class FaqController extends Controller
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
     * Lists all Faq models.
     *
     * @return string
     */
    public function actionIndex($belong)
    {
        $searchModel = new FaqSearch($belong);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'belong' => $belong,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Faq model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($belong)
    {
        $model = new Faq();

        $model->belong = $belong;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $file = UploadedFile::getInstance($model, 'uploadFile');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Faq::UPLOAD_PATH, $belong . '_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->file = $upload['path'];
                        }else {
                            $model->addError('uploadFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }

                    if ($model->save()) {
                        return $this->redirect(['index', 'belong' => $model->belong]);
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
     * Updates an existing Faq model.
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
                    $upload = Gadget::yiiUploadFile($file, Faq::UPLOAD_PATH, $model->belong . '_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->file, Faq::UPLOAD_PATH);
                        $model->file = $upload['path'];
                    }else {
                        $model->addError('uploadFile', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['index', 'belong' => $model->belong]);
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
     * Deletes an existing Faq model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->delete()) {
            Gadget::deleteFile($model->file, Faq::UPLOAD_PATH);
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Faq model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Faq the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Faq::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
