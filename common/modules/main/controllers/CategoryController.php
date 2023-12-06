<?php

namespace common\modules\main\controllers;

use common\components\Gadget;
use common\components\Jdf;
use common\modules\main\models\Category;
use common\modules\main\models\CategorySearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
 */
class CategoryController extends Controller
{

    public $parent = '';

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
                            'actions' => ['index', 'create', 'update', 'preview', 'delete'],
                            'roles' => ['dev', 'admin', 'author'],
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
     * Lists all Category models.
     *
     * @param $belong
     * @return string
     */
    public function actionIndex($belong)
    {
        $searchModel = new CategorySearch();
        $_GET['CategorySearch']['belong'] = $belong;
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'belong' => $belong,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param $type
     * @return string|\yii\web\Response
     */
    public function actionCreate($belong)
    {
        $model = new Category();

        $model->belong = $belong;
        $model->modify_date = Jdf::jmktime();

        if ($model->belong != Category::BELONG_BLOG && $model->belong != Category::BELONG_COURSE && $model->belong != Category::BELONG_TAG && $model->belong != Category::BELONG_ARCHIVE){
            $parents_tree = '<ul id="myUL">';
            $parents_tree .= '<li><span class="caret">هیچکدام</span>
            <input type="radio" name="Category[parent_id]" value="0"></li>';
            $parents_tree .= self::parentsTree(0 , $model->belong);
            $parents_tree .= '</ul>';
        }else{
            $parents_tree = '';
        }

        if ($this->request->isPost) {
            if ($model->load($this->request->post())) {

                if ($model->belong != Category::BELONG_BLOG && $model->belong != Category::BELONG_COURSE && $model->belong != Category::BELONG_TAG && $model->belong != Category::BELONG_ARCHIVE){
                    if (!isset($model->parent_id) || is_null((int)$model->parent_id)){
                        \Yii::$app->session->setFlash('danger', '');
                        return $this->render('create', [
                            'model' => $model,
                            'parents_tree' => $parents_tree,
                        ]);
                    }
                }else{
                    $model->parent_id = Category::PARENT_ID_ROOT;
                }

                $picture = UploadedFile::getInstance($model, 'imageFile');
                if ($picture){
                    $upload = Gadget::yiiUploadFile($picture, 'category');
                    if (!$upload['error']){
                        $model->banner = $upload['path'];
                    }else{
                        \Yii::$app->session->setFlash('danger', '');
                        return $this->render('create', [
                            'model' => $model,
                            'parents_tree' => $parents_tree,
                        ]);
                    }
                }

                if ($model->save()){
                    return $this->redirect(['index', 'belong' => $model->belong]);
                }else{
                    return $this->render('create', [
                        'model' => $model,
                        'parents_tree' => $parents_tree,
                    ]);
                }
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'parents_tree' => $parents_tree,
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $model->modify_date = Jdf::jmktime();

        $parents_tree = '';

        if ($this->request->isPost && $model->load($this->request->post())) {

            $picture = UploadedFile::getInstance($model, 'imageFile');
            if ($picture){
                $upload = Gadget::yiiUploadFile($picture, 'category');
                if (!$upload['error']){
                    Gadget::DeleteFile($model->banner, 'category');
                    $model->banner = $upload['path'];
                }else{
                    \Yii::$app->session->setFlash('danger', '');
                    return $this->render('update', [
                        'model' => $model,
                        'parents_tree' => $parents_tree,
                    ]);
                }
            }

            if ($model->save()){
                return $this->redirect(['index', 'belong' => $model->belong]);
            }else{
                return $this->render('update', [
                    'model' => $model,
                    'parents_tree' => $parents_tree,
                ]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'parents_tree' => $parents_tree,
        ]);
    }


    public function actionPreview($id)
    {
        $model = $this->findModel($id);

        if ($model->preview == Category::PREVIEW_ON){
            $model->preview = Category::PREVIEW_OFF;
        }else{
            $model->preview = Category::PREVIEW_ON;
        }
        $model->save();
        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    /**
     * Deletes an existing Category model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model){
            if ($model->delete()){
                Gadget::DeleteFile($model->banner, 'category');
            }
        }
        return \Yii::$app->response->redirect(\Yii::$app->request->referrer);
    }

    protected function parentsTree($parent_id , $belong)
    {
        $model = Category::find()->where(['parent_id' => $parent_id])->andWhere(['belong' => $belong])->asArray()->all();

        if ($model){
            foreach ($model as $row){
                $this->parent .= '<li><span class="caret">'. $row['title'] .'</span>
                    <input type="radio" name="Category[parent_id]" value="'. $row['id'] .'">';
                $this->parent .= '<ul class="nested">';
                self::parentsTree($row['id'] , $belong);
                $this->parent .= '</ul>';
                $this->parent .= '</li>';
            }
        }
        return $this->parent;
    }

    /**
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(\Yii::t('app', 'The requested page does not exist.'));
    }
}
