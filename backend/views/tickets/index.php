<?php

use common\components\Gadget;
use common\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\TicketsSearch $type */
/** @var common\models\TicketsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست تیکت ها');
if ($type == Tickets::TYPE_DIET) {
    $this->title = Yii::t('app', 'تیکت‌های تغذیه');
}
if ($type == Tickets::TYPE_SUPPORT) {
    $this->title = Yii::t('app', 'تیکت‌های پشتیبانی');
}
if ($type == Tickets::TYPE_COACH) {
    $this->title = Yii::t('app', 'تیکت‌های مهساآنلاین');
}
if ($type == Tickets::TYPE_DEVELOPER) {
    $this->title = Yii::t('app', 'تیکت‌های تیم فنی');
}
$this->params['breadcrumbs'][] = '/ ' . $this->title;
?>

<div class="tickets-index">

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
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'مشاهده'), ['/tickets/chats', 'id' => $model->id], ['class' => 'btn btn-success']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
