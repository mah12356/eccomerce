<?php

use common\components\Jdf;
use common\models\Diet;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\DietSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var common\models\Packages $package */

$this->title = Yii::t('app', 'رژیم‌های ارسال نشده');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="diet-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [
                'attribute' => 'name',
                'content' => function ($model) {
                    return '<p>'. $model->user->name .'</p>';
                }
            ],
            [
                'attribute' => 'lastname',
                'content' => function ($model) {
                    return '<p>'. $model->user->lastname .'</p>';
                }
            ],
            [
                'attribute' => 'mobile',
                'content' => function ($model) {
                    return '<p>'. $model->user->mobile .'</p>';
                }
            ],
            [
                'attribute' => 'package_id',
                'label' => 'پکیج',
                'filter' => ArrayHelper::map($package, 'id', 'name'),
                'content' => function ($model) {
                    if ($model->package) {
                        return '<p>'. $model->package->name .'</p>';
                    }else {
                        return '';
                    }
                }
            ],
            [
                'attribute' => 'date',
                'content' => function ($model) {
                    return '<p>'. Jdf::jdate('Y/m/d', $model->date) .'</p>';
                }
            ],
            [
                'attribute' => 'date_update',
                'content' => function ($model) {
                    return '<p>'. Jdf::jdate('Y/m/d', $model->date_update) .'</p>';
                }
            ],
            [
                'attribute' => 'status',
                'filter' => Diet::getStatus(),
                'content' => function ($model) {
                    switch ($model->status) {
                        case Diet::STATUS_PENDING:
                            return '<p class="text-warning">در انتظار تایید</p>';
                        case Diet::STATUS_WAIT_FOR_ANSWERS:
                            return '<p class="text-primary">در انتظار پاسخ به سوالات</p>';
                        case Diet::STATUS_WAIT_FOR_RESPONSE:
                            return '<p class="text-primary">در انتظار بارگذاری برنامه</p>';
                        case Diet::STATUS_REGIME_UPLOADED:
                            return '<p class="text-success">برنامه غذایی با موفقیت بارگذاری شد</p>';
                        case Diet::STATUS_REGIME_NOT_FOUND:
                            return '<p class="text-danger">برنامه غذایی مناسب پیدا نشد</p>';
                        default:
                            return 'نا مشخص';
                    }
                }
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{ticket}',
                'buttons' => [
                    'ticket' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'تیکت تغذیه'), ['redirect-to-ticket', 'id' => $model->user_id], ['class' => 'btn btn-primary']);
                    },
                ],
            ],
        ],
    ]); ?>


</div>
