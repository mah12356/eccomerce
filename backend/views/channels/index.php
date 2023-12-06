<?php

use common\components\Gadget;
use common\models\Channels;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\ChannelsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست کانال‌ها');
$this->params['breadcrumbs'][] = '/ ' . $this->title;
?>
<div class="channels-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن کانال'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'package_id',
            'name',
            [
                'attribute' => 'avatar',
                'format' => 'html',
                'content' => function ($model) {
                    return '<img class="gridview-banner center-block" src="'. Gadget::showFile($model->avatar, Channels::UPLOAD_PATH) .'">';
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {chats} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'chats' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'پیام ها'), ['chats', 'id' => $model->id], ['class' => 'btn btn-warning']);
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
