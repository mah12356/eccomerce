<?php

namespace common\modules\main\controllers;

use common\components\Gadget;
use common\components\Message;
use common\modules\main\models\Config;
use common\modules\main\models\ConfigSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ConfigController implements the CRUD actions for Config model.
 */
class ConfigController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'create', 'update', 'delete', 'modify'],
                            'roles' => ['dev'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['index', 'update', 'modify', 'modify'],
                            'roles' => ['admin', 'author','coach'],
                        ],
                    ],
                ],
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
     * Lists all Config models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new ConfigSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Config model.
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
     * Creates a new Config model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Config();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['create']);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Config model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())) {
            if ($model->key == Config::KEY_LOGO || $model->key == Config::KEY_FAVICON) {

                switch ($model->key) {
                    case Config::KEY_LOGO:
                        $name = 'logo';
                        $folder = 'config';
                        break;
                    case Config::KEY_FAVICON:
                        $name = 'favicon';
                        $folder = 'favicon';
                        break;
                    default:
                        $name = '';
                        $folder = '';
                        break;
                }
                $file = UploadedFile::getInstance($model, 'file');
                if ($file){
                    $upload = Gadget::yiiUploadFile($file, $folder, $name);
                    if (!$upload['error']){
                        $model->content = $upload['path'];
                    }else{
                        \Yii::$app->session->setFlash('danger', Message::UPLOAD_FAILED);
                        return $this->render('create', [
                            'model' => $model,
                        ]);
                    }
                }
            }

            if ($model->save()){
                return $this->redirect(['index']);
            }else{
                \Yii::$app->session->setFlash('danger', Message::FAILED_TO_EXECUTE);
                return $this->render('create', [
                    'model' => $model,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    public function actionModify($key)
    {
        if ($key == Config::KEY_ANALYZE_FORM || $key == Config::KEY_ANALYZE_TICKET || $key == Config::KEY_DIET_TICKET) {

            $model = Config::findOne(['key' => $key]);

            if ($model->content == '0') {
                $model->content = '1';
            }else {
                $model->content = '0';
            }
            
            $model->save();
        }

        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Config model.
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
     * Finds the Config model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Config the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Config::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
