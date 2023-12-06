<?php

use common\models\Analyze;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Packages $package */
/** @var common\models\AnalyzeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'آنالیز بدنی');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="analyze-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. $model->user->name .'</p>';
                }
            ],

            [
                'attribute' => 'lastname',
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. $model->user->lastname .'</p>';
                }
            ],

            [
                'attribute' => 'mobile',
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. $model->user->mobile .'</p>';
                }
            ],

            [
                'attribute' => 'package_id',
                'filter' => ArrayHelper::map($package, 'id', 'name'),
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. $model->package->name .'</p>';
                }
            ],

            [
                'attribute' => 'status',
                'filter' => Analyze::getStatus(),
                'format' => 'html',
                'content' => function ($model) {
                    switch ($model->status) {
                        case Analyze::STATUS_INACTIVE:
                            return '<p class="text-danger">غیر فعال</p>';
                        case Analyze::STATUS_ACTIVE:
                            return '<p class="text-primary">فعال</p>';
                        case Analyze::STATUS_ANSWERED:
                            return '<p class="text-success">پاسخ داده شده</p>';
                        default:
                            return '<p>نا مشخص</p>';
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'نمایش'), ['view', 'id' => $model->user_id], ['class' => 'btn btn-warning']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
