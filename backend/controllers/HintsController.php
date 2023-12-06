<?php

namespace backend\controllers;

use common\components\Jdf;
use common\models\Hints;
use common\models\HintsSearch;
use common\models\Packages;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * HintsController implements the CRUD actions for Hints model.
 */
class HintsController extends Controller
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
     * Lists all Hints models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new HintsSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Hints model.
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
     * Creates a new Hints model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Hints();

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {
                $model->date = Jdf::jmktime();
                if($model->typePackage == '' || $model->typePackage==null){
                    $model->typePackage = 'public';
                }

                if ($model->save()) {
                    return $this->redirect(['index']);
                }
            }
        }
        $packages = Packages::find()->where(['!=','status',Packages::STATUS_INACTIVE])->all();
        $packages=ArrayHelper::map($packages,'id','name');
        return $this->render('create', [
            'model' => $model,
            'packages'=>$packages,
        ]);
    }

    /**
     * Updates an existing Hints model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $packages = Packages::find()->where(['!=','status',Packages::STATUS_INACTIVE])->all();
        $packages=ArrayHelper::map($packages,'id','name');
        if ($this->request->isPost && $model->load($this->request->post()) ) {
            if($model->typePackage == '' || $model->typePackage==null){
                $model->typePackage = 'public';
            }
            $model->save();
            return $this->redirect(['index']);
        }

        return $this->render('update', [
            'model' => $model,
            'packages'=>$packages
        ]);
    }

    /**
     * Deletes an existing Hints model.
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
     * Finds the Hints model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Hints the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Hints::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
