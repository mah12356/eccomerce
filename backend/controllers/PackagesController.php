<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Course;
use common\models\Packages;
use common\models\PackagesSearch;
use common\models\Register;
use common\models\RegisterSearch;
use common\models\User;
use common\modules\main\models\Category;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * PackagesController implements the CRUD actions for Packages model.
 */
class PackagesController extends Controller
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
     * Lists all Packages models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new PackagesSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Packages model.
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
     * Creates a new Packages model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Packages();

        if (Gadget::getRoleByUserId(\Yii::$app->user->id) == User::ROLE_COACH) {
            $model->coach_id = \Yii::$app->user->id;
        } else {
            $model->coach_id = 1;
        }

        $category = Category::find()->where(['belong' => Category::BELONG_COURSE])->asArray()->all();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $model->start_register = Gadget::JaliliDateToTimeStamp($model->start_register);
                    $model->end_register = Gadget::JaliliDateToTimeStamp($model->end_register);
                    $model->start_date = Gadget::JaliliDateToTimeStamp($model->start_date);

                    if (!is_numeric($model->start_date) || !is_numeric($model->end_register) || !is_numeric($model->start_date)) {
                        \Yii::$app->session->setFlash('danger', 'تاریخ های انتخابی اشتباه است');
                        return $this->render('create', [
                            'model' => $model,
                            'category' => $category,
                        ]);
                    }

                    $file = UploadedFile::getInstance($model, 'posterFile');
                    if ($file) {
                        $upload = Gadget::yiiUploadFile($file, Packages::UPLOAD_PATH, $model->coach_id . '_PACKAGE_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->poster = $upload['path'];
                        } else {
                            $model->addError('posterFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                                'category' => $category,
                            ]);
                        }
                    }
                    
                    $video = UploadedFile::getInstance($model, 'videoFile');
                    if ($video) {
                        $upload = Gadget::yiiUploadFile($video, Packages::UPLOAD_PATH, $model->coach_id . '_PACKAGE_' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $model->video = $upload['path'];
                        } else {
                            $model->addError('videoFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                                'category' => $category,
                            ]);
                        }
                    }

                    if ($model->validate() && $model->save()) {
                        return $this->redirect(['index']);
                    } else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    /**
     * Updates an existing Packages model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

//        if ($model->status != Packages::STATUS_PREPARE) {
//            \Yii::$app->session->setFlash('danger', 'اجازه ویرایش این پکیج را ندارید');
//            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
//        }

        $category = Category::find()->where(['belong' => Category::BELONG_COURSE])->asArray()->all();

        $model->start_register = Gadget::convertToEnglish(Jdf::jdate('Y/m/d', $model->start_register));
        $model->end_register = Gadget::convertToEnglish(Jdf::jdate('Y/m/d', $model->end_register));
        $model->start_date = Gadget::convertToEnglish(Jdf::jdate('Y/m/d', $model->start_date));

        if ($this->request->isPost && $model->load($this->request->post())) {
            try {
                $model->start_register = Gadget::JaliliDateToTimeStamp($model->start_register);
                $model->end_register = Gadget::JaliliDateToTimeStamp($model->end_register);
                $model->start_date = Gadget::JaliliDateToTimeStamp($model->start_date);

                if (!is_numeric($model->start_date) || !is_numeric($model->end_register) || !is_numeric($model->start_date)) {
                    \Yii::$app->session->setFlash('danger', 'تاریخ های انتخابی اشتباه است');
                    return $this->render('create', [
                        'model' => $model,
                        'category' => $category,
                    ]);
                }

                $file = UploadedFile::getInstance($model, 'posterFile');
                if ($file) {
                    $upload = Gadget::yiiUploadFile($file, Packages::UPLOAD_PATH, $model->coach_id . '_PACKAGE_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->poster, Packages::UPLOAD_PATH);
                        $model->poster = $upload['path'];
                    } else {
                        $model->addError('posterFile', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                            'category' => $category,
                        ]);
                    }
                }

                $video = UploadedFile::getInstance($model, 'videoFile');
                if ($video) {
                    $upload = Gadget::yiiUploadFile($video, Packages::UPLOAD_PATH, $model->coach_id . '_PACKAGE_' . Jdf::jmktime());
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->video, Packages::UPLOAD_PATH);
                        $model->video = $upload['path'];
                    } else {
                        $model->addError('videoFile', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                            'category' => $category,
                        ]);
                    }
                }

                if ($model->validate() && $model->save()) {
                    return $this->redirect(['index']);
                } else {
                    \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                }
            } catch (\Exception $exception) {
                \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'category' => $category,
        ]);
    }

    public function actionStudents($id)
    {
        $model = $this->findModel($id);

        $this->view->title = 'شاگردان ' . $model->name;

        $searchModel = new RegisterSearch($id, Register::PAYMENT_ACCEPT);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('students', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionRevoke($id)
    {
        $model = Register::findOne(['id' => $id]);
        if (!$model) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $response = Register::revoke($id);
        if ($response) {
            \Yii::$app->session->setFlash('success', 'کاربر با موفقیت حذف شد');
        }else {
            \Yii::$app->session->setFlash('danger', 'خطا در حذف کاربر');
        }

        return $this->redirect(['students', 'id' => $model->package_id]);
    }

    public function actionStatus($id) {
        $model = $this->findModel($id);

        if ($model->status == Packages::STATUS_PREPARE) {
            $model->status = Packages::STATUS_READY;
        }else {
            $model->status = Packages::STATUS_INACTIVE;
        }

        $model->save();
        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    public function actionPreviewItem($id)
    {
        $model = $this->findModel($id);

        if ($model->preview == Packages::PREVIEW_ON) {
            $model->preview = Packages::PREVIEW_OFF;
        }else {
            $model->preview = Packages::PREVIEW_ON;
        }

        $model->save();

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Packages model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = \Yii::$app->db->beginTransaction();

        if ($model->status == Packages::STATUS_PREPARE) {
            if ($model->delete()) {
                if (Course::deleteAll(['package_id' => $model->id])) {
                    $transaction->commit();
                    Gadget::deleteFile($model->poster, Packages::UPLOAD_PATH);
                }else {
                    $transaction->rollBack();
                }
            }
        }else {
            \Yii::$app->session->setFlash('danger', 'شما اجازه حذف پکیج را ندارید');
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Packages model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Packages the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Packages::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
