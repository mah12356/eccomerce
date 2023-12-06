<?php

/* @var $this yii\web\View */
/* @var $role common\models\User */
/* @var $model common\models\User */
/* @var $form yii\bootstrap5\ActiveForm */

use common\models\User;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

if ($role == User::ROLE_ADMIN){
    $this->title = Yii::t('app', 'افزودن مدیر');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست مدیران'), 'url' => ['users', 'role' => $role]];
    $this->params['breadcrumbs'][] = $this->title;
}
if ($role == User::ROLE_AUTHOR){
    $this->title = Yii::t('app', 'افزودن نویسنده');
    $this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست نویسندگان'), 'url' => ['users', 'role' => $role]];
    $this->params['breadcrumbs'][] = $this->title;
}

?>
<div>
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-2"><?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?></div>
        <div class="col-2"><?= $form->field($model, 'lastname')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-5"><?= $form->field($model, 'username')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-5"><?= $form->field($model, 'email') ?></div>
    </div>

    <div class="row">
        <div class="col-5"><?= $form->field($model, 'mobile') ?></div>
    </div>

    <div class="row">
        <div class="col-5"><?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
