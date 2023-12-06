<?php

namespace frontend\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Navigator;
use common\components\Stack;
use common\models\Analyze;
use common\models\AnalyzeSearch;
use common\models\Register;
use common\modules\main\models\Config;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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

    public function beforeAction($action)
    {
        if (\Yii::$app->user->isGuest) {
            return $this->redirect(['/site/login']);
        }

        if (!isset($_SESSION['pages']) || !$_SESSION['pages']) {
            $_SESSION['pages'] = new Stack();

            $_SESSION['pages']->push(['/site/index', []]);
        }

        (new Navigator())->setUrl($_SESSION['pages'], Url::current());

        if (!isset($_SESSION['appMode'])) {
            return $this->redirect(['/site/index']);
        }

        if ($_SESSION['appMode']) {
            $this->layout = 'app';
        }else {
            $this->layout = 'panel';
        }

        return parent::beforeAction($action);
    }

    /**
     * Lists all Analyze models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $register = Register::find()->where(['user_id' => \Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
            ->asArray()->all();

        $model = Analyze::find()->where(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->andWhere(['user_id' => \Yii::$app->user->id, 'status' => Analyze::STATUS_INACTIVE])->all();
        foreach ($model as $item) {
            $item->status = Analyze::STATUS_ACTIVE;
            $item->save(false);
        }

        $model = Analyze::find()->where(['user_id' => \Yii::$app->user->id])
            ->andWhere(['IN', 'register_id', ArrayHelper::map($register, 'id', 'id')])
            ->andWhere(['!=', 'status', Analyze::STATUS_INACTIVE])->orderBy(['date' => SORT_DESC])->with('package')->asArray()->all();

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
            ],
            'categories' => [],
        ];

        foreach ($model as $item) {
            if ($item['status'] == Analyze::STATUS_ANSWERED) {
                $weightPackage['series'][0]['data'][] = $item['weight'];
                $weightPackage['categories'][] = $item['package']['name'];

                $bellyWaist['series'][0]['data'][] = $item['belly'];
                $bellyWaist['categories'][] = 'دور کمر (' . $item['waist'] . ')';
            }
        }

        return $this->render('index', [
            'model' => $model,
            'weightPackage' => $weightPackage,
            'bellyWaist' => $bellyWaist,
        ]);
    }

    public function actionAnswer($id)
    {
        $access = Config::getKeyContent(Config::KEY_ANALYZE_FORM);
        if (!$access) {
            \Yii::$app->session->setFlash('danger', 'در حال حاضر وارد کردن اطلاعات مسدود شده است');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                try {
                    $currentTimeStamp = Jdf::jmktime();
                    if (isset($_FILES['frontFile']) && $_FILES['frontFile']['tmp_name']) {
                        $uploadFront = Gadget::phpUploadFile($_FILES['frontFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_FRONT_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                        if ($uploadFront['error']) {
                            \Yii::$app->session->setFlash('danger', $uploadFront['message']);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                        $model->front_image = $uploadFront['path'];
                    }
                    if (isset($_FILES['sideFile']) && $_FILES['sideFile']['tmp_name']) {
                        $uploadSide = Gadget::phpUploadFile($_FILES['sideFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_SIDE_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                        if ($uploadSide['error']) {
                            \Yii::$app->session->setFlash('danger', $uploadSide['message']);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                        $model->side_image = $uploadSide['path'];
                    }
                    if (isset($_FILES['backFile']) && $_FILES['backFile']['tmp_name']) {
                        $uploadBack = Gadget::phpUploadFile($_FILES['backFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_BACK_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                        if ($uploadBack['error']) {
                            \Yii::$app->session->setFlash('danger', $uploadBack['message']);
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                        $model->back_image = $uploadBack['path'];
                    }

                    $model->updated_at = $currentTimeStamp;
                    $model->status = Analyze::STATUS_ANSWERED;

                    if ($model->validate() && $model->save()) {
                        return $this->redirect(['index']);
                    }else {
                        \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }catch (\Exception $exception) {
                    \Yii::$app->session->setFlash('danger', Message::UNKNOWN_ERROR);
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
     * Displays a single Analyze model.
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
     * Updates an existing Analyze model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $access = Config::getKeyContent(Config::KEY_ANALYZE_FORM);
        if (!$access) {
            \Yii::$app->session->setFlash('danger', 'در حال حاضر ویرایش اطلاعات مسدود شده است');
            return $this->redirect(['index']);
        }

        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $currentTimeStamp = Jdf::jmktime();
            if (isset($_FILES['frontFile']) && $_FILES['frontFile']['tmp_name']) {
                $uploadFront = Gadget::phpUploadFile($_FILES['frontFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_FRONT_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                if ($uploadFront['error']) {
                    \Yii::$app->session->setFlash('danger', $uploadFront['message']);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                $frontImage = $model->front_image;
                $model->front_image = $uploadFront['path'];
            }
            if (isset($_FILES['sideFile']) && $_FILES['sideFile']['tmp_name']) {
                $uploadSide = Gadget::phpUploadFile($_FILES['sideFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_SIDE_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                if ($uploadSide['error']) {
                    \Yii::$app->session->setFlash('danger', $uploadSide['message']);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                $sideImage = $model->side_image;
                $model->side_image = $uploadSide['path'];
            }
            if (isset($_FILES['backFile']) && $_FILES['backFile']['tmp_name']) {
                $uploadBack = Gadget::phpUploadFile($_FILES['backFile'], ['png', 'jpg', 'jpeg'], \Yii::$app->user->id . '_BACK_' . $currentTimeStamp, Analyze::UPLOAD_PATH);
                if ($uploadBack['error']) {
                    \Yii::$app->session->setFlash('danger', $uploadBack['message']);
                    return $this->render('update', [
                        'model' => $model,
                    ]);
                }
                $backImage = $model->back_image;
                $model->back_image = $uploadBack['path'];
            }

            $model->updated_at = $currentTimeStamp;

            if ($model->validate() && $model->save()) {
                if (isset($_FILES['frontFile']) && $_FILES['frontFile']['tmp_name']) {
                    Gadget::deleteFile($frontImage, Analyze::UPLOAD_PATH);
                }
                if (isset($_FILES['sideFile']) && $_FILES['sideFile']['tmp_name']) {
                    Gadget::deleteFile($sideImage, Analyze::UPLOAD_PATH);
                }
                if (isset($_FILES['backFile']) && $_FILES['backFile']['tmp_name']) {
                    Gadget::deleteFile($backImage, Analyze::UPLOAD_PATH);
                }
                return $this->redirect(['index']);
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
