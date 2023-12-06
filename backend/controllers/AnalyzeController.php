<?php

namespace backend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\models\Analyze;
use common\models\AnalyzeSearch;
use common\models\Answers;
use common\models\Chats;
use common\models\Course;
use common\models\Diet;
use common\models\Packages;
use common\models\Register;
use common\models\Tickets;
use common\models\User;
use yii\base\InvalidRouteException;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * AnalyzeController implements the CRUD actions for Analyze model.
 */
class AnalyzeController extends Controller
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
     * Lists all Analyze models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $course = Course::find()->where(['belong' => Course::BELONG_ANALYZE])->asArray()->all();
        $package = Packages::find()->where(['IN', 'id', ArrayHelper::map($course, 'package_id', 'package_id')])
            ->andWhere(['status' => Packages::STATUS_READY])->orderBy(['id' => SORT_DESC])->asArray()->all();

        $searchModel = new AnalyzeSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'package' => $package,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Analyze model.
     * @param int $id ID
     * @return string|\yii\console\Response|\yii\web\Response
     * @throws InvalidRouteException
     */

    public function actionMm()
    {
        $model = Analyze::find()->where(['package_id' => 24])
            ->all();
        //Gadget::preview($model);
        foreach ($model as $item){
            $analyze = new Analyze();
            $analyze->user_id = $item->user_id;
            $analyze->register_id = $item->register_id;
            $analyze->package_id = 24;
            $analyze->course_id = 112;
            $analyze->date = Jdf::jmktime();
            $analyze->updated_at = Jdf::jmktime();
            $analyze->save(false);
        }
    }

    public function actionView($id)
    {
        $user = User::findOne(['id' => $id]);
        if (!$user) {
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }

        $model = Analyze::find()->where(['user_id' => $id, 'status' => Analyze::STATUS_ANSWERED])
            ->with('package')->asArray()->all();

        $searchModel = new AnalyzeSearch();
        $_GET['AnalyzeSearch']['user_id'] = $id;
        $_GET['AnalyzeSearch']['status'] = Analyze::STATUS_ANSWERED;
        $dataProvider = $searchModel->search($this->request->queryParams);

        $weightPackage = [
            'series' => [
                [
                    'name' => 'نمودار وزن به پکیج',
                    'data' => [],
                ]
            ],
            'categories' => [],
        ];
        $bellyWaist = [
            'series' => [
                [
                    'name' => 'نمودار دور کمر به دور شکم',
                    'data' => [],
                ],
                'categories' => [],
            ],
        ];

        foreach ($model as $item) {
            if ($item['status'] == Analyze::STATUS_ANSWERED) {
                $weightPackage['series'][0]['data'][] = $item['weight'];
                $weightPackage['categories'][] = $item['package']['name'];

                $bellyWaist['series'][0]['data'][] = $item['belly'];
                $bellyWaist['categories'][] = 'دور کمر (' . $item['waist'] . ')';
            }
        }


        $ticket = Tickets::find()->where(['user_id' => $id, 'type' => Tickets::TYPE_ANALYZE])->with('chats')->asArray()->one();
        if (!$ticket) {
            return $this->redirect(['index']);
        }

        $chatModel = new Chats();

        $reference = new Chats();

        $chatModel->parent_id = $ticket['id'];
        $chatModel->belong = Chats::BELONG_TICKET;
        $chatModel->sender = Chats::SENDER_ADMIN;

        if ($this->request->isPost) {
            if ($chatModel->load($this->request->post())) {
                try {
                    $image = UploadedFile::getInstance($chatModel, 'imageFile');
                    $audio = UploadedFile::getInstance($chatModel, 'audioFile');
                    $video = UploadedFile::getInstance($chatModel, 'videoFile');
                    $document = UploadedFile::getInstance($chatModel, 'documentFile');

                    if (!$chatModel->text && !$image && !$audio && !$video && !$document) {
                        return $this->render('view', [
                            'user' => $user,
                            'model' => $model,
                            'searchModel' => $searchModel,
                            'dataProvider' => $dataProvider,
                            'weightPackage' => $weightPackage,
                            'bellyWaist' => $bellyWaist,
                            'ticket' => $ticket,
                            'chatModel' => $chatModel,
                            'reference' => $reference,
                        ]);
                    }

                    if ($image) {
                        $upload = Gadget::yiiUploadFile($image, Chats::UPLOAD_PATH, $chatModel->parent_id . '__IMAGE__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $chatModel->image = $upload['path'];
                        } else {
                            $chatModel->addError('imageFile', 'خطا در بارگذاری');
                            return $this->render('view', [
                                'user' => $user,
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'weightPackage' => $weightPackage,
                                'bellyWaist' => $bellyWaist,
                                'ticket' => $ticket,
                                'chatModel' => $chatModel,
                                'reference' => $reference,
                            ]);
                        }
                    }
                    if ($audio) {
                        $upload = Gadget::yiiUploadFile($audio, Chats::UPLOAD_PATH, $chatModel->parent_id . '__AUDIO__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $chatModel->audio = $upload['path'];
                        } else {
                            $chatModel->addError('audioFile', 'خطا در بارگذاری');
                            return $this->render('view', [
                                'user' => $user,
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'weightPackage' => $weightPackage,
                                'bellyWaist' => $bellyWaist,
                                'ticket' => $ticket,
                                'chatModel' => $chatModel,
                                'reference' => $reference,
                            ]);
                        }
                    }
                    if ($video) {
                        $upload = Gadget::yiiUploadFile($video, Chats::UPLOAD_PATH, $chatModel->parent_id . '__VIDEO__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $chatModel->video = $upload['path'];
                        } else {
                            $chatModel->addError('videoFile', 'خطا در بارگذاری');
                            return $this->render('view', [
                                'user' => $user,
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'weightPackage' => $weightPackage,
                                'bellyWaist' => $bellyWaist,
                                'ticket' => $ticket,
                                'chatModel' => $chatModel,
                                'reference' => $reference,
                            ]);
                        }
                    }
                    if ($document) {
                        $upload = Gadget::yiiUploadFile($document, Chats::UPLOAD_PATH, $chatModel->parent_id . '__DOCUMENT__' . Jdf::jmktime());
                        if (!$upload['error']) {
                            $chatModel->document = $upload['path'];
                        } else {
                            $chatModel->addError('documentFile', 'خطا در بارگذاری');
                            return $this->render('view', [
                                'user' => $user,
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'weightPackage' => $weightPackage,
                                'bellyWaist' => $bellyWaist,
                                'ticket' => $ticket,
                                'chatModel' => $chatModel,
                                'reference' => $reference,
                            ]);
                        }
                    }

//                    $chatModel->validate();
//                    Gadget::preview($chatModel->errors);

                    if ($chatModel->saveMessage()) {
                        return $this->redirect(['view', 'id' => $id]);
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                    }
                } catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
                }
            }
        }

//        Gadget::preview($weightPackage, false);
//        Gadget::preview($bellyWaist);

        return $this->render('view', [
            'user' => $user,
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'weightPackage' => $weightPackage,
            'bellyWaist' => $bellyWaist,
            'ticket' => $ticket,
            'chatModel' => $chatModel,
            'reference' => $reference,
        ]);
    }

    /**
     * Creates a new Analyze model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Analyze();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Analyze model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Analyze model.
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
     * Finds the Analyze model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Analyze the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Analyze::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
