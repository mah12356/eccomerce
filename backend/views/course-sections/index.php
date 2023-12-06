<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\CourseSections;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Course $course */
/** @var common\models\CourseSectionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'مدیریت جلسات');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست دوره ها'), 'url' => ['/course/index', 'package_id' => $course->package_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-sections-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن گروه جلسات به دوره'), ['create', 'course_id' => $course->id], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'گروه جلسات',
                'format' => 'html',
                'content' => function ($model) {
                    return '<p>'. $model->group->title .'</p>';
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{delete}',
                'buttons' => [
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
