<?php

namespace backend\controllers;

use common\components\Gadget;
use common\models\Course;
use common\models\Options;
use common\models\Questions;
use common\models\QuestionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * QuestionsController implements the CRUD actions for Questions model.
 */
class QuestionsController extends Controller
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
     * Lists all Questions models.
     *
     * @return string|\yii\console\Response|\yii\web\Response
     */
    public function actionIndex($course_id)
    {
//        $course = Course::findOne(['id' => $course_id]);
//        if (!$course) {
//            return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
//        }

        $searchModel = new QuestionsSearch($course_id);
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
//            'course' => $course,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Questions model.
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
     * Creates a new Questions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate($course_id)
    {
        $model = new Questions();
        $transaction = \Yii::$app->db->beginTransaction();

        $model->course_id = $course_id;

        if (!isset($_SESSION['options'])) {
            $_SESSION['options'] = [];
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                foreach ($_SESSION['options'] as $item) {
                    $option = new Options();
                    $option->question_id = $model->id;
                    $option->content = $item;

                    if (!$option->save()) {
                        $transaction->rollBack();
                        \Yii::$app->session->setFlash('danger', '1');
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
                $_SESSION['options'] = [];
                $transaction->commit();
                return $this->redirect(['index', 'course_id' => $model->course_id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Questions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $transaction = \Yii::$app->db->beginTransaction();

        if (!isset($_SESSION['options'])) {
            $_SESSION['options'] = array();
            $options = Options::findAll(['question_id' => $id]);
            foreach ($options as $item) {
                array_push($_SESSION['options'], $item->content);
            }
        }

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            if (Options::findAll(['question_id' => $id])) {
                if (Options::deleteAll(['question_id' => $id])) {
                    foreach ($_SESSION['options'] as $item) {
                        $option = new Options();
                        $option->question_id = $model->id;
                        $option->content = $item;

                        if (!$option->save()) {
                            $transaction->rollBack();
                            \Yii::$app->session->setFlash('danger', 'گزینه های سوال ذخیره نشد');
                            return $this->render('create', [
                                'model' => $model,
                            ]);
                        }
                    }
                }else {
                    $transaction->rollBack();
                    \Yii::$app->session->setFlash('danger', 'خطا در ویرایش گزینه ها');
                    return $this->render('create', [
                        'model' => $model,
                    ]);
                }
            }
            $_SESSION['options'] = [];
            $transaction->commit();
            return $this->redirect(['index', 'course_id' => $model->course_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionSetOption($content = null)
    {
        if (!isset($_SESSION['options'])) {
            $_SESSION['options'] = [];
        }

        if ($content != null) {
            if (!in_array($content, $_SESSION['options'])) {
                array_push($_SESSION['options'], $content);
            }
        }

        $result = '';
        foreach ($_SESSION['options'] as $item) {
            $result .= '<a class="btn btn-secondary mx-1">'. $item .'</a>';
        }

        return $result;
    }

    /**
     * Deletes an existing Questions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $transaction = \Yii::$app->db->beginTransaction();

        if ($model->delete()) {
            if (!Options::findAll(['question_id' => $model->id]) || Options::deleteAll(['question_id' => $model->id])) {
                $transaction->commit();
            }else {
                $transaction->rollBack();
            }
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Finds the Questions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Questions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Questions::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
