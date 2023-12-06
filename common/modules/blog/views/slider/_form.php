<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Slider $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="slider-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'text')->textInput(['maxlength' => true]) ?>

    <div class="row">
        <div class="col-3"><?= $form->field($model, 'btn')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
