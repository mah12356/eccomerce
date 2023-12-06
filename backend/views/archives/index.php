<?php

use common\components\Gadget;
use common\models\Archives;
use common\modules\main\models\MetaTags;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ArchivesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'آرشیو ها');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archives-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن آرشیو'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'category_id',
                'value' => 'category.title',
            ],
            'title',
            'subject',
            'intro',
            'link:url',
            [
                'attribute' => 'poster',
                'content' => function ($model) {
                    return '<img class="gridview-banner center-block" src="'. Gadget::showFile($model->poster, Archives::UPLOAD_PATH) .'" alt="'. $model->alt .'">';
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {files} {meta-tags} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'files' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویدیو ها'), ['/archive-content/index', 'archive' => $model->id], ['class' => 'btn btn-info']);
                    },
                    'meta-tags' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'مدیریت سئو'), ['/main/meta-tags/index', 'parent_id' => $model->id, 'belong' => MetaTags::BELONG_ARCHIVE],
                            ['class' => 'btn btn-warning']);
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
