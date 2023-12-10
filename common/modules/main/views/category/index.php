<?php

use common\components\Gadget;
use common\components\Jdf;
use common\modules\blog\models\Posts;
use common\modules\main\models\Category;
use common\modules\main\models\MetaTags;
use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\modules\main\models\CategorySearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $belong common\modules\main\controllers\CategoryController */

if ($belong == Category::BELONG_BLOG) {
    $title = 'لیست دسته‌های بلاگ';
    $btn_msg = 'افزودن بلاگ';
}elseif ($belong == Category::BELONG_PRODUCT){
    $title = 'دسته‌بندی محصولات';
    $btn_msg = 'افزودن دسته';
}elseif ($belong == Category::BELONG_JOB) {
    $title = 'لیست مشاغل';
    $btn_msg = 'افزودن شغل';
}elseif ($belong == Category::BELONG_TAG) {
    $title = 'لیست برچسب ها';
    $btn_msg = 'افزودن برچسب';
}else{
    $title = 'دسته بندی دوره ها';
    $btn_msg = 'افزودن دسته';
}
$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = '/ '.$this->title;
?>
<div class="category-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', $btn_msg), ['create', 'belong' => $belong], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #039be5'],
                'label' => 'تاریخ نشر',
                'value' => function ($model) {
                    return '<p>'. Gadget::ConvertToEnglish(Jdf::jdate('Y/m/d' , $model->modify_date)) .'</p>';
                },
            ],
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #039be5'],
                'label' => 'بنر',
                'value' => function ($model) {
                    return '<img src="'. Gadget::ShowFile($model->banner, 'category') .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {article} {create-product} {product-list} {meta-tag} {preview} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'article' => function ($url, $model) {
                        if ($model->belong == Category::BELONG_TAG) {
                            return Html::a(Yii::t('app', 'متن ها'), ['/blog/posts/index', 'page_id' => $model->id, 'belong' => Posts::BELONG_TAGS], ['class' => 'btn btn-success']);
                        }
                    },
                    'create-product' => function ($url, $model) {
                        if ($model->belong == Category::BELONG_PRODUCT){
                            return Html::a(Yii::t('app', 'افزودن محصول'), ['/shop/product/create', 'category_id' => $model->id],
                                ['class' => 'btn btn-success']);
                        }else{
                            return null;
                        }
                    },
                    'product-list' => function ($url, $model) {
                        if ($model->belong == Category::BELONG_PRODUCT){
                            return Html::a(Yii::t('app', 'لیست محصولات'), ['/shop/product/index', 'category_id' => $model->id],
                                ['class' => 'btn btn-info']);
                        }else{
                            return null;
                        }
                    },
                    'meta-tag' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'مدیریت سئو'), ['/main/meta-tags/index', 'parent_id' => $model->id, 'belong' => MetaTags::CATEGORY], ['class' => 'btn btn-dark']);
                    },
                    'preview' => function ($url, $model) {
                        if ($model->belong != Category::BELONG_JOB && $model->belong != Category::BELONG_BLOG && $model->belong != Category::BELONG_TAG){
                            if ($model->preview == Category::PREVIEW_ON){
                                return Html::a(Yii::t('app', 'عدم نمایش'), ['preview', 'id' => $model->id], ['class' => 'btn btn-warning']);
                            }else{
                                return Html::a(Yii::t('app', 'نمایش'), ['preview', 'id' => $model->id], ['class' => 'btn btn-info']);
                            }
                        }else{
                            return null;
                        }

                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
