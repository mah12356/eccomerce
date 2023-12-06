<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Message;
use common\models\Coach;
use common\models\CoachSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CoachController implements the CRUD actions for Coach model.
 */
class CoachController extends Controller
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
     * Lists all Coach models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new CoachSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Coach model.
     * @param int $id ID
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Coach::find()->where(['user_id' => $id])->with('user')->one();

        if (!$model) {
            return $this->redirect(['create', 'user_id' => $id]);
        }
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Creates a new Coach model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($user_id)
    {
        $coach = Coach::findOne(['user_id' => $user_id]);
        if ($coach) {
            return $this->redirect(['view', 'id' => $user_id]);
        }

        $model = new Coach();

        $model->user_id = $user_id;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->validate()) {
                try {
                    $avatar = UploadedFile::getInstance($model, 'avatarFile');
                    $poster = UploadedFile::getInstance($model, 'posterFile');
                    $video = UploadedFile::getInstance($model, 'videoFile');

                    if ($avatar) {
                        $upload = Gadget::yiiUploadFile($avatar, 'coach');
                        if (!$upload['error']) {
                            $model->avatar = $upload['path'];
                        }else {
                            $model->addError('avatarFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
                    if ($poster) {
                        $upload = Gadget::yiiUploadFile($poster, 'coach');
                        if (!$upload['error']) {
                            $model->poster = $upload['path'];
                        }else {
                            $model->addError('posterFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
                    if ($video) {
                        $upload = Gadget::yiiUploadFile($video, 'coach');
                        if (!$upload['error']) {
                            $model->video = $upload['path'];
                        }else {
                            $model->addError('videoFile', Message::UPLOAD_FAILED);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }

                    if ($model->save()) {
                        return $this->redirect(['view', 'id' => $model->user_id]);
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
     * Updates an existing Coach model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->validate()) {
            try {
                $avatar = UploadedFile::getInstance($model, 'avatarFile');
                $poster = UploadedFile::getInstance($model, 'posterFile');
                $video = UploadedFile::getInstance($model, 'videoFile');

                if ($avatar) {
                    $upload = Gadget::yiiUploadFile($avatar, 'coach');
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->avatar, 'coach');
                        $model->avatar = $upload['path'];
                    }else {
                        $model->addError('avatarFile', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }
                if ($poster) {
                    $upload = Gadget::yiiUploadFile($poster, 'coach');
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->poster, 'coach');
                        $model->poster = $upload['path'];
                    }else {
                        $model->addError('posterFile', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }
                if ($video) {
                    $upload = Gadget::yiiUploadFile($video, 'coach');
                    if (!$upload['error']) {
                        Gadget::deleteFile($model->video, 'coach');
                        $model->video = $upload['path'];
                    }else {
                        $model->addError('videoFile', Message::UPLOAD_FAILED);
                        return $this->render('update', [
                            'model' => $model,
                        ]);
                    }
                }

                if ($model->save()) {
                    return $this->redirect(['view', 'id' => $model->user_id]);
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
     * Deletes an existing Coach model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Coach model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Coach the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Coach::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
