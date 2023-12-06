<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="faq-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'question')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'answer')->textarea(['rows' => 6]) ?>

    <div class="row">
        <div class="col-3"><?= $form->field($model, 'sort')->textInput(['maxlength' => true]) ?></div>
        <div class="col-4"><?= $form->field($model, 'uploadFile')->fileInput() ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
