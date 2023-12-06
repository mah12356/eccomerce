<?php

use common\components\Jdf;
use common\models\Community;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\CommunitySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'نظر کاربران');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="community-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'label' => 'مقاله',
                'content' => function ($model) {
                    return '<p>'. $model->article->title .'</p>';
                }
            ],
            [
                'attribute' => 'user_id',
                'format' => 'html',
                'label' => 'نام و نام خانوادگی',
                'content' => function ($model) {
                    return '<p>'. $model->user->name .' '. $model->user->lastname .'</p>';
                }
            ],
            'text:ntext',
            [
                'attribute' => 'date',
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. Jdf::jdate('Y/m/d', $model->date) .'</p>';
                }
            ],
            'reply',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{reply} {submit} {deny} {delete}',
                'buttons' => [
                    'reply' => function ($url, $model) {
                        if ($model->reply == null && $model->status != Community::STATUS_DENY) {
                            return Html::a(Yii::t('app', 'پاسخ'), ['reply', 'id' => $model->id], ['class' => 'btn btn-primary']);
                        }else {
                            return null;
                        }
                    },
                    'submit' => function ($url, $model) {
                        if ($model->status == Community::STATUS_PENDING) {
                            return Html::a(Yii::t('app', 'تایید'), ['submit', 'id' => $model->id], [
                                'class' => 'btn btn-success',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                ],
                            ]);
                        }else {
                            return null;
                        }
                    },
                    'deny' => function ($url, $model) {
                        if ($model->status == Community::STATUS_PENDING) {
                            return Html::a(Yii::t('app', 'رد'), ['deny', 'id' => $model->id], [
                                'class' => 'btn btn-warning',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                ],
                            ]);
                        }else {
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
