<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Packages;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\PackagesSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست پکیج ها');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="packages-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن پکیج'), ['create'], ['class' => 'btn btn-primary']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'attribute' => 'poster',
                'content' => function ($model) {
                    return '<img class="gridview-banner" src="'. Gadget::showFile($model->poster, Packages::UPLOAD_PATH) .'" alt="">';
                },
            ],
            [
                'format' => 'html',
                'attribute' => 'category',
                'value' => 'cat.title',
            ],
            'name',
            'discount',
            [
                'format' => 'html',
                'attribute' => 'date',
                'content' => function ($model) {
                    $data = '<p><b>شروع ثبت نام : </b>'. Jdf::jdate('Y/m/d', $model->start_register) .'</p>';
                    $data .= '<p><b>پایان ثبت نام : </b>'. Jdf::jdate('Y/m/d', $model->end_register) .'</p>';
                    $data .= '<p><b>شروع کلاس : </b>'. Jdf::jdate('Y/m/d', $model->start_date) .'</p>';
                    $data .= '<p><b>پایان کلاس : </b>'. Jdf::jdate('Y/m/d', $model->start_date + ($model->period * 86400)) .'</p>';

                    return $data;
                },
            ],
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {course} {students} {status} {delete} {preview}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
//                        if ($model->status == Packages::STATUS_PREPARE) {
//                            return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
//                        }else {
//                            return null;
//                        }
                    },
                    'course' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'دوره ها'), ['/course/index', 'package_id' => $model->id], ['class' => 'btn btn-warning my-2']);
                    },
                    'students' => function ($url, $model) {
                        if ($model->status != Packages::STATUS_PREPARE) {
                            return Html::a(Yii::t('app', 'شاگردان'), ['students', 'id' => $model->id], ['class' => 'btn btn-primary my-2']);
                        }else {
                            return null;
                        }
                    },
                    'status' => function ($url, $model) {
                        if ($model->status == Packages::STATUS_PREPARE) {
                            return Html::a(Yii::t('app', 'تکمیل فرآیند'), ['status', 'id' => $model->id], [
                                'class' => 'btn btn-info my-2',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]);
                        }elseif ($model->status == Packages::STATUS_READY) {
                            return Html::a(Yii::t('app', 'غیر فعال کردن'), ['status', 'id' => $model->id], [
                                'class' => 'btn btn-dark my-2',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]);
                        }else {
                            return '<p class="btn btn-danger">غیر فعال</p>';
                        }
                    },
                    'delete' => function ($url, $model) {
                        if ($model->status == Packages::STATUS_PREPARE) {
                            return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger my-2',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]);
                        }else {
                            return null;
                        }
                    },
                    'preview'=> function ($url, $model) {
                        if ($model->status == Packages::STATUS_READY) {
                            if ($model->preview == Packages::PREVIEW_OFF) {
                                return Html::a(Yii::t('app', 'نمایش'), ['preview-item', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
                            }else {
                                return Html::a(Yii::t('app', 'عدم نمایش'), ['preview-item', 'id' => $model->id], ['class' => 'btn btn-warning my-2']);
                            }
                        }else {
                            return null;
                        }
                    },
                ],
            ],
        ],
    ]); ?>


</div>
