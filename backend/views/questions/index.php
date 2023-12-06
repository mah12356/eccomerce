<?php

use common\models\Questions;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
///** @var common\models\Course $course */
/** @var common\models\QuestionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست سوالات');
//$this->params['breadcrumbs'][] = ['label' => '/ لیست دوره ها', 'url' => ['/course/index', 'package_id' => $course->package_id]];
$this->params['breadcrumbs'][] = '/ ' . $this->title;
?>
<div class="questions-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'تعریف سوال'), ['create', 'course_id' => 0], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'type',
                'filter' => Questions::getType(),
                'content' => function ($model) {
                    switch ($model->type) {
                        case Questions::TYPE_TEXT:
                            return '<p>نوشته</p>';
                        case Questions::TYPE_NUMBER:
                            return '<p>عددی</p>';
                        case Questions::TYPE_DROPDOWN:
                            return '<p>کشویی</p>';
                        case Questions::TYPE_CHECKBOX:
                            return '<p>انتخابی</p>';
                        case Questions::TYPE_RADIO:
                            return '<p>گزینه ای</p>';
                        default:
                            return '<p>نا مشخص</p>';
                    }
                }
            ],

            'title',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {options} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']);
                    },
                    'options' => function ($url, $model) {
                        if ($model->type != Questions::TYPE_TEXT && $model->type != Questions::TYPE_NUMBER) {
                            return Html::a(Yii::t('app', 'لیست گزینه ها'), ['/options/index', 'question_id' => $model->id], ['class' => 'btn btn-warning']);
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
