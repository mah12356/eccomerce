<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\ArchiveContent $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="archive-content-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'sort')->textInput() ?></div>
        <div class="col-md-5"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'uploadFile')->fileInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'text')->textarea(['rows' => 15]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
