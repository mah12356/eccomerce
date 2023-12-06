<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Booking $model */

$this->title = Yii::t('app', 'ثبت گفتگو');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ تماس تلفنی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="booking-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
