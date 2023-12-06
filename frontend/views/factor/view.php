<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Factor;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Factor $model */

$this->title = 'فاکتور : ' . $model->id;
YiiAsset::register($this);
?>
<div class="row">
    <div class="col-md-5 col-12 mx-auto">
        <?php if ($model->payment == Factor::PAYMENT_PENDING) { ?>
            <p>
                <?= Html::a(Yii::t('app', 'پرداخت'), ['/factor/pay-factor', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                <?= Html::a(Yii::t('app', 'انصراف'), ['/factor/delete', 'id' => $model->id], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                        'method' => 'post',
                    ],
                ]) ?>
            </p>
        <?php } ?>
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                [
                    'attribute' => 'method',
                    'value' => function ($model) {
                        switch ($model->method) {
                            case Factor::METHOD_ONLINE:
                                return 'آنلاین';
                            case Factor::METHOD_OFFLINE:
                                return 'آفلاین';
                            default:
                                return 'نا مشخص';
                        }
                    }
                ],
                [
                    'attribute' => 'amount',
                    'value' => function ($model) {
                        return Gadget::convertToPersian($model->amount) . ' (تومان)';
                    }
                ],
                [
                    'attribute' => 'payment',
                    'format' => 'html',
                    'value' => function ($model) {
                        switch ($model->payment) {
                            case Factor::PAYMENT_REJECT:
                                return '<div class="text-danger">پرداخت ناموفق</div>';
                            case Factor::PAYMENT_PENDING:
                                return '<div class="text-primary">در انتظار پرداخت</div>';
                            case Factor::PAYMENT_ACCEPT:
                                return '<div class="text-success">پرداخت شده</div>';
                            default:
                                return '<div>نا مشخص</div>';
                        }
                    }
                ],
                [
                    'attribute' => 'publish_date',
                    'format' => 'html',
                    'value' => function ($model) {
                        return Jdf::jdate('Y/m/d', $model->publish_date);
                    }
                ],
            ],
        ]) ?>
    </div>
</div>