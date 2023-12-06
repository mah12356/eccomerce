<?php

use common\components\Gadget;
use common\models\Course;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\controllers\CourseController $package_id */
/** @var common\models\CourseSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست دوره ها');
$this->params['breadcrumbs'][] = ['label' => '/ لیست پکیج ها', 'url' => ['/packages/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن دوره'), ['create', 'package_id' => $package_id], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'attribute' => 'belong',
                'content' => function ($model) {
                    if ($model->belong == Course::BELONG_LIVE) {
                        return '<p class="text-success">پخش زنده</p>';
                    }elseif ($model->belong == Course::BELONG_OFFLINE) {
                        return '<p class="text-primary">آفلاین</p>';
                    }elseif ($model->belong == Course::BELONG_ANALYZE) {
                        return '<p><b>آنالیز</b></p>';
                    }elseif ($model->belong == Course::BELONG_DIET) {
                        return '<p class="text-danger">برنامه غذایی</p>';
                    }else {
                        return '<p class="text-danger">رژیم شوک</p>';
                    }
                },
            ],
            'name',
            'price',

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {sections} {questions} {banner}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
                    },
                    'sections' => function ($url, $model) {
                        if ($model->belong != Course::BELONG_DIET && $model->belong != Course::BELONG_SHOCK_DIET && $model->belong != Course::BELONG_ANALYZE) {
                            return Html::a(Yii::t('app', 'مدیریت جلسات'), ['/course-sections/index', 'course_id' => $model->id], ['class' => 'btn btn-warning my-2']);
                        }else {
                            return null;
                        }
                    },
                    'questions' => function ($url, $model) {
                        if ($model->belong == Course::BELONG_DIET) {
//                            return Html::a(Yii::t('app', 'تعریف سوال'), ['/questions/index', 'course_id' => $model->id], ['class' => 'btn btn-primary my-2']);
                            return null;
                        }else {
                            return null;
                        }
                    },
                    'banner'=>function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش بنر'), ['update-banner', 'id' => $model->id], ['class' => 'btn btn-success my-2']);
                        
                    },
                    // 'delete' => function ($url, $model) {
                        // return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                        //     'class' => 'btn btn-danger my-2',
                        //     'data' => [
                        //         'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                        //         'method' => 'post',
                        //     ],
                        // ]);
                    // },
                ],
            ],
        ],
    ]); ?>


</div>
