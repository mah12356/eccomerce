<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Packages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\RegisterSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست شاگردان');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست پکیج ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'format' => 'html',
                'label' => 'نام',
                'content' => function ($model) {
                    if ($model->user) {
                        return '<p>'. $model->user->name .'</p>';
                    }else {
                        return '<p>نامشخص</p>';
                    }
                },
            ],
            [
                'attribute' => 'lastname',
                'format' => 'html',
                'label' => 'نام خانوادگی',
                'content' => function ($model) {
                    if ($model->user) {
                        return '<p>'. $model->user->lastname .'</p>';
                    }else {
                        return '<p>نامشخص</p>';
                    }
                },
            ],
            [
                'attribute' => 'mobile',
                'format' => 'html',
                'label' => 'شماره موبایل',
                'content' => function ($model) {
                    if ($model->user) {
                        return '<p>'. $model->user->mobile .'</p>';
                    }else {
                        return '<p>نامشخص</p>';
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف از پکیج'), ['revoke', 'id' => $model->id], [
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