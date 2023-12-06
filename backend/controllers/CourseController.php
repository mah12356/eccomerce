<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Course;
use common\models\CourseMedia;
use common\models\CourseSearch;
use common\models\CourseSections;
use common\models\Packages;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CourseController implements the CRUD actions for Course model.
 */
class CourseController extends Controller
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
     * Lists all Course models.
     *
     * @return string
     */
    public function actionIndex($package_id)
    {
        $searchModel = new CourseSearch($package_id);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'package_id' => $package_id,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Course model.
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
     * Creates a new Course model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($package_id)
    {
        $package = Packages::findOne(['id' => $package_id]);
        if (!$package) {
            return $this->redirect(['/packages/index']);
        }

//        if ($package->status != Packages::STATUS_PREPARE) {
//            \Yii::$app->session->setFlash('danger', 'شما اجازه ساخت دوره جدید را ندارید');
//            return $this->redirect(['index', 'package_id' => $package_id]);
//        }


        $model = new Course();

        $model->package_id = $package_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {

                if ($model->belong == Course::BELONG_DIET && $model->count == 0) {
                    $model->addError('count', 'لطفا تعداد برنامه های غذایی را مشخص کنید');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                $file = UploadedFile::getInstance($model, 'posterFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, Course::UPLOAD_PATH, \Yii::$app->user->id . '_' .$model->package_id . '_COURSE_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        $model->poster = $upload['path'];
                    }else {
                        $model->addError('posterFile', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['index', 'package_id' => $model->package_id]);
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
     * Updates an existing Course model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        $package = Packages::findOne(['id' => $model->package_id]);

        if ($package->status != Packages::STATUS_PREPARE) {
            \Yii::$app->session->setFlash('danger', 'شما اجازه ویرایش این دوره را ندارید');
            return $this->redirect(['index', 'package_id' => $model->package_id]);
        }

        if ($this->request->isPost && $model->load($this->request->post())) {

            if ($model->belong == Course::BELONG_DIET && $model->count == 0) {
                $model->addError('count', 'لطفا تعداد برنامه های غذایی را مشخص کنید');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            $file = UploadedFile::getInstance($model, 'posterFile');
            if ($file) {
                $upload = Gadget::yiiUploadFile($file, Course::UPLOAD_PATH, \Yii::$app->user->id . '_' .$model->package_id . '_COURSE_' . Jdf::jmktime());
                if (!$upload['error']) {
                    Gadget::deleteFile($model->poster, Course::UPLOAD_PATH);
                    $model->poster = $upload['path'];
                }else {
                    $model->addError('posterFile', Message::UPLOAD_FAILED);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
            }

            if ($model->save()) {
                return $this->redirect(['index', 'package_id' => $model->package_id]);
            }else {
                \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Course model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $package = Packages::findOne(['id' => $model->package_id]);

        $transaction = \Yii::$app->db->beginTransaction();

        if ($package->status != Packages::STATUS_PREPARE) {
            \Yii::$app->session->setFlash('danger', 'شما اجازه حذف این دوره را ندارید');
        }else {
            if ($model->delete() && CourseSections::deleteAll(['course_id' => $id])) {
                $transaction->commit();
            }
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Course model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Course the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Course::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
    public function actionUpdateBanner($id)
    {
        $model = Course::findOne(['id' => $id]);
        if ($model){
             if ($this->request->isPost) {
                 $file = UploadedFile::getInstance($model, 'posterFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, Course::UPLOAD_PATH, \Yii::$app->user->id . '_' .$model->package_id . '_COURSE_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        $model->poster = $upload['path'];
                        if($model->save())
                        {
                            \Yii::$app->session->setFlash('success',"تغیر بنر با مونفیت انجام شد");
                              return $this->render('update-banner', [
                            'model' => $model,
                        ]);
                        }
                    }else {
                        $model->addError('posterFile', Message::UPLOAD_FAILED);
                        return $this->render('update-banner', [
                            'model' => $model,
                        ]);
                    }
                }
                 
            }else{
                 return $this->render('update-banner', [
                            'model' => $model,
                        ]);
            }
            
        }else{
            return $this->goBack();
        }
    }
}
