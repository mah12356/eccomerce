<?php

use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Sections $model */
/** @var common\models\LogSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'شرکت کنندگان جلسه : {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => '/ لیست جلسات', 'url' => ['/sections/index', 'group' => $model->group]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div>

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
        ],
    ]); ?>

</div>
