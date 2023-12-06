<?php

/** @var yii\web\View $this */
/** @var common\models\SectionsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */


use common\components\Jdf;
use common\models\Groups;
use common\models\Sections;
use yii\helpers\Html;
use yii\grid\GridView;

$url = Yii::$app->urlManager;

$this->title = Yii::t('app', 'لیست گروه جلسات');
$this->params['breadcrumbs'][] = '/ ' . $this->title;
?>
<div class="course-media-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'ثبت گروه جلسه جدید'), ['create-group'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            [
                'format' => 'html',
                'attribute' => 'type',
                'filter' => Groups::getType(),
                'content' => function ($model) {
                    switch ($model->type) {
                        case Groups::TYPE_LIVE:
                            return '<p class="text-primary">پخش زنده</p>';
                        case Groups::TYPE_OFFLINE:
                            return '<p class="text-warning">آفلاین</p>';
                        default:
                            return '<p class="text-danger">نا مشخص</p>';
                    }
                },
            ],
            'sections_count',
            [
                'format' => 'html',
                'label' => 'زمان‌بندی',
                'content' => function ($model) {
                    $data = '<p><b>تاریخ شروع : </b>'. Jdf::jdate('Y/m/d - l', $model->start_date) .'</p>';
                    $data .= '<p><b>ساعت شروع : </b>'. $model->start_time .'</p>';
                    $data .= '<p><b>ساعت پایان : </b>'. $model->finish_time .'</p>';
                    return $data;
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{sections}',
                'buttons' => [

                    'sections' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'لیست جلسات'), ['index', 'group' => $model->id], ['class' => 'btn btn-primary']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>


