<?php

use common\components\Jdf;
use common\models\Sections;
use yii\helpers\Html;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\controllers\SectionsController $group */
/** @var common\models\SectionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست جلسات');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست گروه جلسات'), 'url' => ['/sections/groups']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'ثبت جلسه جدید'), ['create', 'group' => $group], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'format' => 'html',
                'attribute' => 'mood',
                'filter' => Sections::getMood(),
                'content' => function ($model) {
                    switch ($model->mood) {
                        case Sections::MOOD_PENDING:
                            return '<p class="text-primary">در انتظار پخش</p>';
                        case Sections::MOOD_READY:
                            return '<p class="text-warning">آماده پخش</p>';
                        case Sections::MOOD_PLAYING:
                            return '<p class="text-info">در حال پخش</p>';
                        case Sections::MOOD_COMPLETE:
                            return '<p class="text-success">اتمام جلسه و ذخیره شده</p>';
                        case Sections::MOOD_FAILED:
                            return '<p class="text-danger">اتمام جلسه و ذخیره نشده</p>';
                        default:
                            return '<p class="text-danger">نا مشخص</p>';
                    }
                },
            ],
            [
                'format' => 'html',
                'label' => 'زمان‌بندی',
                'content' => function ($model) {
                    $data = '<p><b>تاریخ برگذاری : </b>'. Jdf::jdate('Y/m/d - l', $model->date) .'</p>';
                    $data .= '<p><b>ساعت شروع : </b>'. Jdf::jdate('H:i', $model->start_at) .'</p>';
                    $data .= '<p><b>ساعت پایان : </b>'. Jdf::jdate('H:i', $model->end_at) .'</p>';
                    return $data;
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{auto} {manual} {view} {participants} {update} {delete}',
                'buttons' => [
                    'auto' => function ($url, $model) {
                        if ($model->status == Sections::STATUS_COMPLETE && $model->mood == Sections::MOOD_COMPLETE) {
                            return null;
                        }else {
                            if ($model->type == Sections::TYPE_LIVE) {
                                return Html::a(Yii::t('app', 'ذخیره جلسه'), ['save-stream', 'id' => $model->id], ['class' => 'btn btn-info']);
                            }else {
                                return null;
                            }
                        }
                    },
                    'manual' => function ($url, $model) {
                        if ($model->status == Sections::STATUS_COMPLETE && $model->mood == Sections::MOOD_COMPLETE) {
                            return null;
                        }else {
                            return Html::a(Yii::t('app', 'بارگذاری جلسه'), ['upload-section', 'id' => $model->id], ['class' => 'btn btn-success']);
                        }
                    },
                    'view' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'جزئیات'), ['view', 'id' => $model->id], ['class' => 'btn btn-warning']);
                    },
                    'participants' => function ($url, $model) {
                        if ($model->status == Sections::STATUS_COMPLETE && $model->mood == Sections::MOOD_COMPLETE) {
                            if ($model->type == Sections::TYPE_LIVE) {
                                return Html::a(Yii::t('app', 'شرکت کنندگان'), ['participants', 'id' => $model->id], ['class' => 'btn btn-success']);
                            }else {
                                return null;
                            }
                        }else {
                            return null;
                        }
                    },
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
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
