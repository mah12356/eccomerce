<?php

namespace backend\controllers;

use common\components\Gadget;
use common\models\Course;
use common\models\Diet;
use common\models\DietSearch;
use common\models\Packages;
use common\models\Tickets;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * DietController implements the CRUD actions for Diet model.
 */
class DietController extends Controller
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
     * Lists all Diet models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new DietSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionSpecify()
    {
        $searchModel = new DietSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        $regimeCourses = Course::find()->where(['IN', 'belong', [Course::BELONG_DIET, Course::BELONG_SHOCK_DIET]])
            ->asArray()->all();
        $package = Packages::find()->where(['status' => Packages::STATUS_READY])
            ->andWhere(['IN', 'id', ArrayHelper::map($regimeCourses, 'package_id', 'package_id')])->asArray()->all();

        return $this->render('specify', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'package' => $package,
        ]);
    }

    public function actionRedirectToTicket($id)
    {
        $user = User::findOne(['id' => $id]);
        if (!$user) {
            \Yii::$app->session->setFlash('danger', 'اطلاعات کاربر یافت نشد');
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }
        $ticket = Tickets::findOne(['user_id' => $id]);
        if (!$ticket) {
            Tickets::defineTickets($id);
            \Yii::$app->session->setFlash('danger', 'محتوای تیکت یافت نشد لطفا مجدد تلاش کنید');
            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
        }



        return $this->redirect(['/tickets/chats', 'id' => $ticket->id]);
    }

    /**
     * Displays a single Diet model.
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
     * Creates a new Diet model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Diet();

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
     * Updates an existing Diet model.
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
     * Deletes an existing Diet model.
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
     * Finds the Diet model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Diet the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Diet::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
