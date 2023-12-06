<?php

namespace backend\controllers;

use common\models\Community;
use common\models\CommunitySearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * CommunityController implements the CRUD actions for Community model.
 */
class CommunityController extends Controller
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
     * Lists all Community models.
     *
     * @return string
     */
    public function actionIndex($parent_id, $belong, $status)
    {
        $searchModel = new CommunitySearch($parent_id, $belong, $status);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionReply(int $id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            $model->status = Community::STATUS_SUBMIT;
            if ($model->save(false)) {
                return $this->redirect(['index', 'parent_id' => 0, 'belong' => Community::BELONG_BLOGS, 'status' => Community::STATUS_PENDING]);
            }
        }

        return $this->render('reply', [
            'model' => $model,
        ]);
    }

    public function actionSubmit(int $id) {
        $model = $this->findModel($id);

        $model->status = Community::STATUS_SUBMIT;
        $model->save(false);

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    public function actionDeny(int $id) {
        $model = $this->findModel($id);

        $model->status = Community::STATUS_DENY;
        $model->save(false);

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Community model.
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
     * Finds the Community model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Community the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Community::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
