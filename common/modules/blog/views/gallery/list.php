<?php

use common\components\Gadget;
use common\modules\blog\models\Gallery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\modules\blog\controllers\GalleryController $parent_id */
/** @var common\modules\blog\controllers\GalleryController $belong */
/** @var common\modules\blog\models\GallerySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'پیشرفت شاگردان');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="gallery-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن عکس'), ['compare'], ['class' => 'btn btn-success']) ?>
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
                    return '<img src="'. Gadget::ShowFile($model->image, 'gallery') .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                },
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
