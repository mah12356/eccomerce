<?php

use common\models\Hints;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\HintsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'اعلانات');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="hints-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن اعلان'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'type',
                'filter' => Hints::getType(),
                'content' => function ($model) {
                    switch ($model->type) {
                        case Hints::TYPE_NOTIFICATION:
                            return 'اطلاعیه';
                        case Hints::TYPE_INSTANT:
                            return 'فوری';
                        default:
                            return 'نا مشخص';
                    }
                }
            ],
            [
                'attribute' => 'belong',
                'filter' => Hints::getBelong(),
                'content' => function ($model) {
                    switch ($model->belong) {
                        case Hints::BELONG_HOME_PAGE:
                            return 'صفحه اصلی';
                        case Hints::BELONG_DIET_TICKET:
                            return 'تیکت تغذیه';
                        default:
                            return 'نا مشخص';
                    }
                }
            ],
            'title',
            'link',

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
