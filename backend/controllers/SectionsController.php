<?php

namespace backend\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\VideoStreams;
use common\models\Course;
use common\models\Groups;
use common\models\GroupsSearch;
use common\models\Log;
use common\models\LogSearch;
use common\models\Packages;
use common\models\SectionContents;
use common\models\Sections;
use common\models\SectionsSearch;
use yii\console\Response;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * SectionsController implements the CRUD actions for Sections model.
 */
class SectionsController extends Controller
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

    public function actionGroups()
    {
        $searchModel = new GroupsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('groups', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreateGroup()
    {
        $model = new Groups();
        $transaction = \Yii::$app->db->beginTransaction();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $days = $model->interval;
                $explodeDate = explode('/', Gadget::convertToEnglish($model->start_date));
                $model->start_date = Jdf::jmktime(0, 0, 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
                $model->interval = 1;

                if ($model->save()) {
                    $response = (new Sections())->defineSections($model->id, $days);
                    if (!$response['error']) {
                        $transaction->commit();
                        return $this->redirect(['groups']);
                    }else {
                        $transaction->rollBack();
                        \Yii::$app->session->setFlash('danger', $response['message']);
                    }
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create-group', [
            'model' => $model,
        ]);
    }

    /**
     * Lists all Sections models.
     *
     * @return string|Response|\yii\web\Response
     */
    public function actionIndex($group)
    {
        $searchModel = new SectionsSearch($group);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'group' => $group,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSaveStream($id)
    {
        $model = Sections::findOne(['id' => $id, 'type' => Sections::TYPE_LIVE]);
        if (!$model) {
            \Yii::$app->session->setFlash('danger', 'جلسه مورد نظر یافت نشد');
            return $this->redirect(['index', 'group' => $model->group]);
        }

        if ($model->mood != Sections::MOOD_PLAYING) {
            \Yii::$app->session->setFlash('danger', 'کلاس شما هنوز پخش نشده است');
            return $this->redirect(['index', 'group' => $model->group]);
        }

        $uploadSectionVideos = VideoStreams::uploadSectionVideos($model->id);
        if ($uploadSectionVideos['error']) {
            $model->status = Sections::STATUS_COMPLETE;
            $model->mood = Sections::MOOD_FAILED;
            $model->save(false);

            \Yii::$app->session->setFlash('danger', $uploadSectionVideos['message']);
            return $this->redirect(['index', 'group' => $model->group]);
        }

        $addSectionFiles = Sections::addSectionFiles($model->id, $uploadSectionVideos['data']);
        if (!$addSectionFiles['error']) {
            \Yii::$app->session->setFlash('success', 'با موفقیت ذخیره شد');
        }else {
            \Yii::$app->session->setFlash('danger', $addSectionFiles['message']);
        }

        return $this->redirect(['index', 'group' => $model->group]);
    }

    public function actionUploadSection($id) {
        $model = $this->findModel($id);

        $transaction = \Yii::$app->db->beginTransaction();

        if ($this->request->isPost) {
            if (!isset($_POST['Sections']['file']) || !$_POST['Sections']['file']) {
                $model->addError('file', 'نام فایل نمی تواند خالی باشد');
                return $this->render('upload-section', [
                    'model' => $model,
                ]);
            }

            $moveFile = Gadget::renameFile($_POST['Sections']['file'], 'tmp', 'sections/' . $model->group, $model->title . '_' . Jdf::jmktime());
            if (!$moveFile['error']) {

                $content = new SectionContents();

                $content->section_id = $model->id;
                $content->title = 'پارت اول';
                $content->file = $moveFile['name'];

                if ($content->save()) {

                    $model->status = Sections::STATUS_COMPLETE;
                    $model->mood = Sections::MOOD_COMPLETE;

                    if ($model->save()) {
                        $transaction->commit();
                        \Yii::$app->session->setFlash('success', 'فایل با موفقیت ذخیره شد');
                        return $this->redirect(['index', 'group' => $model->group]);
                    }else {
                        $transaction->rollBack();
                        \Yii::$app->session->setFlash('danger', 'جلسه مورد نظر ذخیره نشد');
                    }
                }else {
                    \Yii::$app->session->setFlash('danger', 'فایل مورد نظر ذخیره نشد');
                }
            }else {
                \Yii::$app->session->setFlash('danger', 'فایل مورد نظر یافت نشد');
            }
        }

        return $this->render('upload-section', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Sections model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView(int $id)
    {
        $model = $this->findModel($id);
        $content = SectionContents::find()->where(['section_id' => $id])->asArray()->all();
        
        return $this->render('view', [
            'model' => $model,
            'content' => $content,
        ]);
    }

    /**
     * Creates a new Sections model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($group)
    {
        $model = new Sections();

        $model->group = $group;

        $gp = Groups::find()->where(['id' => $group])->one();
        if (!$gp) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                if (!$model->date) {
                    \Yii::$app->session->setFlash('danger', 'تاریخ برگزاری نمی تواند خالی باشد');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }

                try {
                    $model->date = Gadget::JaliliDateToTimeStamp($model->date);

                    $explodeDate = explode('/', Jdf::jdate('Y/m/d', $model->date));
                    $explodeStart = explode(':', $model->start_at);
                    $explodeEnd = explode(':', $model->end_at);

                    $model->type = $gp->type;
                    $model->start_at = Jdf::jmktime((int)$explodeStart[0], (int)$explodeStart[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
                    $model->end_at = Jdf::jmktime((int)$explodeEnd[0], (int)$explodeEnd[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);


                    if ($model->save()) {
                        return $this->redirect(['index', 'group' => $model->group]);
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
     * Updates an existing Sections model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if (!$model->date) {
                \Yii::$app->session->setFlash('danger', 'تاریخ برگزاری نمی تواند خالی باشد');
                return $this->render('update', [
                    'model' => $model,
                ]);
            }

            try {
                $model->date = Gadget::JaliliDateToTimeStamp($model->date);

                $explodeDate = explode('/', Jdf::jdate('Y/m/d', $model->date));
                $explodeStart = explode(':', $model->start_at);
                $explodeEnd = explode(':', $model->end_at);

                $model->start_at = Jdf::jmktime((int)$explodeStart[0], (int)$explodeStart[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
                $model->end_at = Jdf::jmktime((int)$explodeEnd[0], (int)$explodeEnd[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);

                if ($model->save()) {
                    return $this->redirect(['index', 'group' => $model->group]);
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
     * Deletes an existing Sections model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);

        $course = Course::find()->where(['id' => $model->course_id])->with('package')->asArray()->one();
        if (!$course) {
            \Yii::$app->session->setFlash('danger', 'دوره ای مرتبط با این جلسه یافت نشد');
            return $this->redirect(['index', 'course_id' => $model->course_id]);
        }
        if ($course['package']['status'] != Packages::STATUS_PREPARE) {
            \Yii::$app->session->setFlash('danger', 'اجازه جذف جلسه برای این دوره را ندارید');
            return $this->redirect(['index', 'course_id' => $model->course_id]);
        }

        if ($model->status == Sections::STATUS_PROCESS && $model->mood == Sections::MOOD_PENDING) {
            $model->delete();
        }else {
            \Yii::$app->session->setFlash('danger', 'اجازه جذف جلسه را ندارید');
        }

        return $this->redirect(['index', 'course_id' => $model->course_id]);
    }


    public function actionParticipants($id)
    {
        $model = $this->findModel($id);

        $searchModel = new LogSearch();
        $_GET['LogSearch']['page_type'] = Log::PAGE_TYPE_LIVE;
        $_GET['LogSearch']['page_id'] = $model->id;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('participants', [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Finds the Sections model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Sections the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Sections::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
