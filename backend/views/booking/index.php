<?php

use common\components\Jdf;
use common\models\Booking;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Booking $month */
/** @var common\models\BookingSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'تماس تلفنی');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="booking-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'ثبت گفتگو'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'name',
            'mobile',
            [
                'attribute' => 'description',
                'format' => 'html',
                'content' => function ($model) {
                    return $model->description;
                }
            ],
            [
                'attribute' => 'month',
                'format' => 'html',
                'filter' => $month,
                'content' => function ($model) {
                    return Jdf::jdate('Y/m/d', $model->month);
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{change-status}',
                'buttons' => [
                    'change-status' => function ($url, $model) {
                        if ($model->status == Booking::STATUS_PENDING) {
                            return Html::a(Yii::t('app', 'بررسی شده'), ['change-status', 'id' => $model->id], ['class' => 'btn btn-primary']);
                        }else {
                            return null;
                        }
                    },
                ],
            ],
        ],
    ]); ?>


</div>
