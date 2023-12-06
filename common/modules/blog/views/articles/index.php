<?php

use common\components\Gadget;
use common\components\Jdf;
use common\modules\blog\models\Articles;
use common\modules\blog\models\Posts;
use common\modules\main\models\MetaTags;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\ArticlesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست مقالات');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="articles-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'ساخت مقاله جدید'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            [
                'attribute' => 'category_id',
                'format' => 'html',
                'label' => 'دسته مقاله',
                'value' => 'cat.title',
            ],
            'title',
            
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #0d6efd'],
                'label' => 'پستر',
                'value' => function ($model) {
                    return '<img src="'. Gadget::ShowFile($model->poster, 'articles') .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                },
            ],
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #0d6efd'],
                'label' => 'بنر',
                'value' => function ($model) {
                    return '<img src="'. Gadget::ShowFile($model->banner, 'articles') .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {posts} {tags} {meta-tag} {update_date} {preview} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary my-2']);
                    },
                    'posts' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'متن‌ها'), ['/blog/posts/index', 'page_id' => $model->id, 'belong' => Posts::BELONG_ARTICLES], ['class' => 'btn btn-success my-2']);
                    },
                    'tags' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'برچسب‌ها'), ['/blog/tags/index', 'article_id' => $model->id], ['class' => 'btn btn-warning my-2']);
                    },
                    'meta-tag' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'مدیریت سئو'), ['/main/meta-tags/index', 'parent_id' => $model->id, 'belong' => MetaTags::BELONG_BLOG], ['class' => 'btn btn-dark my-2']);
                    },
                    'update_date' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش تاریخ'), ['/blog/articles/update-date', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
                    },
                    'preview' => function ($url, $model) {
                        if ($model->publish == Articles::PUBLISH_TRUE) {
                            if ($model->preview == Articles::PREVIEW_ON){
                                return Html::a(Yii::t('app', 'عدم نمایش'), ['preview', 'id' => $model->id], ['class' => 'btn btn-warning my-2']);
                            }else{
                                return Html::a(Yii::t('app', 'نمایش'), ['preview', 'id' => $model->id], ['class' => 'btn btn-info my-2']);
                            }
                        }else {
                            return null;
                        }
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger my-2',
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
