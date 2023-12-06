<?php

namespace common\modules\main\controllers;

use common\modules\main\models\MetaTags;
use common\modules\main\models\MetaTagsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MetaTagsController implements the CRUD actions for MetaTags model.
 */
class MetaTagsController extends Controller
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
     * Lists all MetaTags models.
     *
     * @return string
     */
    public function actionIndex($parent_id, $belong)
    {
        $searchModel = new MetaTagsSearch();
        $_GET['MetaTagsSearch']['parent_id'] = $parent_id;
        $_GET['MetaTagsSearch']['belong'] = $belong;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'parent_id' => $parent_id,
            'belong' => $belong,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    /**
     * Creates a new MetaTags model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($parent_id, $belong)
    {
        $model = new MetaTags();

        $model->parent_id = $parent_id;
        $model->belong = $belong;

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing MetaTags model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing MetaTags model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the MetaTags model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return MetaTags the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = MetaTags::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
