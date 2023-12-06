<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Coach $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="coach-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-3"><?= $form->field($model, 'instagram_id')->textInput(['maxlength' => true]) ?></div>
        <div class="col-3"><?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'bio')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>


    <div class="row">
        <div class="col-3"><?= $form->field($model, 'avatarFile')->fileInput() ?></div>
        <div class="col-3"><?= $form->field($model, 'posterFile')->fileInput() ?></div>
    </div>

    <div class="form-group mt-5">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
