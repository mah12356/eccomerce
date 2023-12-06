<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Channels $model */
/** @var common\models\Channels $packages */

$this->title = Yii::t('app', 'ویرایش : {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست کانال‌ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="channels-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'packages' => $packages,
    ]) ?>

</div>
