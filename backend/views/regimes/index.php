<?php

use common\models\Regimes;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\RegimesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'برنامه های غذایی');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="regimes-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن برنامه جدید'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'type',
                'filter' => Regimes::getType(),
                'format' => 'html',
                'content' => function ($model) {
                    switch ($model->type) {
                        case Regimes::TYPE_DIET:
                            return '<p class="text-success">رژیم غذایی</p>';
                        case Regimes::TYPE_SHOCK:
                            return '<p class="text-primary">رژیم شوک</p>';
                        default:
                            return '<p class="text-danger">نامشخص</p>';
                    }
                },
            ],
            'calorie',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {download} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'download' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'دانلود فایل'), ['download-regime', 'id' => $model->id], ['class' => 'btn btn-warning']);
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
