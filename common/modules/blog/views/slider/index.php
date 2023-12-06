<?php

use common\components\Gadget;
use common\modules\blog\models\Slider;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\modules\blog\controllers\SliderController $page_id */
/** @var common\modules\blog\controllers\SliderController $belong */
/** @var common\modules\blog\models\SliderSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'اسلایدر‌ها');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="slider-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن اسلایدر'), ['create', 'page_id' => $page_id, 'belong' => $belong], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #039be5'],
                'label' => 'عکس',
                'value' => function ($model) {
                    return '<img src="'. Gadget::ShowFile($model->image, 'sliders') .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                },
            ],
            'title',
            'text',
            'btn',
            'url:url',
            //'image',
            //'alt',
            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {delete}',
                'buttons' => [
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
