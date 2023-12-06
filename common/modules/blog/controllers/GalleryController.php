<?php

namespace common\modules\blog\controllers;

use common\components\Gadget;
use common\components\Message;
use common\modules\blog\models\Gallery;
use common\modules\blog\models\GallerySearch;
use yii\db\StaleObjectException;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use yii\web\UploadedFile;

/**
 * GalleryController implements the CRUD actions for Gallery model.
 */
class GalleryController extends Controller
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
     * Lists all Gallery models.
     *
     * @return string
     */
    public function actionIndex($parent_id, $belong)
    {
        $searchModel = new GallerySearch();
        $_GET['GallerySearch']['parent_id'] = $parent_id;
        $_GET['GallerySearch']['belong'] = $belong;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'parent_id' => $parent_id,
            'belong' => $belong,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Gallery model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|Response
     */
    public function actionCreate($parent_id, $belong)
    {
        $model = new Gallery();

        $model->parent_id = $parent_id;
        $model->belong = $belong;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model, 'file');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, 'gallery');
                    if (!$upload['error']) {
                        $model->image = $upload['path'];
                    }else {
                        $model->addError('file', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }else {
                    $model->addError('file', 'عکس نمیتواند خالی باشد');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
                if ($model->save()) {
                    return $this->redirect(['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]);
                }else {
                    \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    return $this->render('create', [
                        'model' => $model,
                    ]);
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
     * Updates an existing Gallery model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'file');
            if ($file) {
                $upload = Gadget::yiiUploadFile($file, 'gallery');
                if (!$upload['error']) {
                    Gadget::deleteFile($model->image, 'gallery');
                    $model->image = $upload['path'];
                }else {
                    $model->addError('file', Message::UPLOAD_FAILED);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

            if ($model->save()) {
                return $this->redirect(['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]);
            }else {
                \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                return $this->render('update', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionList()
    {
        $searchModel = new GallerySearch();
        $_GET['GallerySearch']['parent_id'] = 0;
        $_GET['GallerySearch']['belong'] = Gallery::BELONG_HOME;
        $_GET['GallerySearch']['type'] = Gallery::TYPE_COMPARE;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('list', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCompare()
    {
        $model = new Gallery();

        $model->parent_id = 0;
        $model->belong = Gallery::BELONG_HOME;
        $model->type = Gallery::TYPE_COMPARE;

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model, 'file');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, 'gallery');
                    if (!$upload['error']) {
                        $model->image = $upload['path'];
                    }else {
                        $model->addError('file', Message::UPLOAD_FAILED);
                        return $this->render('compare', [
                            'model' => $model,
                        ]);
                    }
                }else {
                    $model->addError('file', 'عکس نمیتواند خالی باشد');
                    return $this->render('compare', [
                        'model' => $model,
                    ]);
                }
                if ($model->save()) {
                    return $this->redirect(['list']);
                }else {
                    \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    return $this->render('compare', [
                        'model' => $model,
                    ]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('compare', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Gallery model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws StaleObjectException
     */
    public function actionDelete(int $id): Response
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Gadget::deleteFile($model->image, 'gallery');
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Gallery model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Gallery the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Gallery::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
