<?php

/* @var $this yii\web\View */
/* @var $role common\models\User */
/* @var $model common\models\User */
/* @var $form yii\bootstrap5\ActiveForm */

use common\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;



?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-5"><?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
