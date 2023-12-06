<?php

namespace common\modules\blog\controllers;

use common\components\Gadget;
use common\components\Message;
use common\modules\blog\models\Slider;
use common\modules\blog\models\SliderSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * SliderController implements the CRUD actions for Slider model.
 */
class SliderController extends Controller
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
     * Lists all Slider models.
     *
     * @return string
     */
    public function actionIndex($page_id, $belong)
    {
        $searchModel = new SliderSearch();
        $_GET['SliderSearch']['page_id'] = $page_id;
        $_GET['SliderSearch']['belong'] = $belong;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'page_id' => $page_id,
            'belong' => $belong,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Slider model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($page_id, $belong)
    {
        $model = new Slider();

        $model->page_id = $page_id;
        $model->belong = $belong;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model, 'file');

                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, 'sliders');
                    if (!$upload['error']) {
                        $model->image = $upload['path'];
                        if ($model->save()) {
                            return $this->redirect(['index', 'page_id' => $model->page_id, 'belong' => $model->belong]);
                        }else {
                            \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        }
                    }else {
                        $model->addError('file', Message::UPLOAD_FAILED);
                    }
                }else {
                    $model->addError('file', 'عکس نمی‌تواند خالی باشد');
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
     * Updates an existing Slider model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');

            if ($file) {
                $upload = Gadget::yiiUploadFile($file, 'sliders');
                if (!$upload['error']) {
                    Gadget::deleteFile($model->image, 'sliders');
                    $model->image = $upload['path'];
                }else {
                    $model->addError('file', Message::UPLOAD_FAILED);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

            if ($model->save()) {
                return $this->redirect(['index', 'page_id' => $model->page_id, 'belong' => $model->belong]);
            }else {
                \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);

            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Slider model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete())
            Gadget::deleteFile($model->image, 'sliders');

        return $this->redirect(['index', 'page_id' => $model->page_id, 'belong' => $model->belong]);
    }

    /**
     * Finds the Slider model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Slider the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Slider::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
