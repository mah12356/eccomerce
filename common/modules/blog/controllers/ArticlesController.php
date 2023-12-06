<?php

namespace common\modules\blog\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\modules\blog\models\Articles;
use common\modules\blog\models\ArticlesSearch;
use common\modules\main\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ArticlesController implements the CRUD actions for Articles model.
 */
class ArticlesController extends Controller
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
     * Lists all Articles models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ArticlesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }
  
  	public function actionShow($id) {
		header('Location: https://mahsaonlin.com/site/article-view?id=' . $id);
      	exit;
    }

    /**
     * Creates a new Articles model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Articles();

        $model->modify_date = Jdf::jmktime();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $file = UploadedFile::getInstance($model, 'posterFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, 'articles');
                    if (!$upload['error']) {
                        $model->poster = $upload['path'];
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
                $image = UploadedFile::getInstance($model, 'imageFile');
                if ($image) {
                    $upload = Gadget::yiiUploadFile($image, 'articles');
                    if (!$upload['error']) {
                        $model->banner = $upload['path'];
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
                $video = UploadedFile::getInstance($model, 'videoFile');
                if ($video) {
                    $upload = Gadget::yiiUploadFile($video, 'articles');
                    if (!$upload['error']) {
                        $model->video = $upload['path'];
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
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
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Articles model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->modify_date = Jdf::jmktime();

        if ($this->request->isPost && $model->load($this->request->post())) {
            $file = UploadedFile::getInstance($model, 'posterFile');
            if ($file) {
                $upload = Gadget::yiiUploadFile($file, 'articles');
                if (!$upload['error']) {
                    Gadget::deleteFile($model->poster, 'articles');
                    $model->poster = $upload['path'];
                }else {
                    \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            $image = UploadedFile::getInstance($model, 'imageFile');
            if ($image) {
                $upload = Gadget::yiiUploadFile($image, 'articles');
                if (!$upload['error']) {
                    Gadget::deleteFile($model->banner, 'articles');
                    $model->banner = $upload['path'];
                }else {
                    \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }
            $video = UploadedFile::getInstance($model, 'videoFile');
            if ($video) {
                $upload = Gadget::yiiUploadFile($video, 'articles');
                if (!$upload['error']) {
                    Gadget::deleteFile($model->video, 'articles');
                    $model->video = $upload['path'];
                }else {
                    \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
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
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionPreview($id)
    {
        $model = $this->findModel($id);

        if ($model->preview == Category::PREVIEW_ON){
            $model->preview = Category::PREVIEW_OFF;
        }else{
            $model->preview = Category::PREVIEW_ON;
        }
        $model->save();
        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    public function actionUpdateDate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {

            $explodeDate = [];
            $explodeTime = [];

            if (isset($_POST['Articles']['modify_date']) && $_POST['Articles']['modify_date']) {
                $explodeDate = explode('/', $_POST['Articles']['modify_date']);
            }

            if (isset($_POST['Articles']['time']) && $_POST['Articles']['time']) {
                $explodeTime = explode(':', $_POST['Articles']['time']);
            }

            if ($explodeDate && $explodeTime) {
                $model->modify_date = Jdf::jmktime((int)$explodeTime[0], (int)$explodeTime[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
            }elseif ($explodeDate && !$explodeTime) {
                $model->modify_date = Jdf::jmktime(8, 0, 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
            }else {
                \Yii::$app->session->setFlash('danger', 'اطلاعات وارد شده ناقص است');
                return $this->render('update-date', [
                    'model' => $model,
                ]);
            }

            if ($model->save()) {
                return $this->redirect(['index']);
            }else {
                \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
            }
        }

        return $this->render('update-date', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Articles model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->delete()) {
            Gadget::deleteFile($model->banner, 'articles');
            Gadget::deleteFile($model->video, 'articles');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Articles model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Articles the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Articles::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
