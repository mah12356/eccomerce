<?php

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Config */
/* @var $form yii\bootstrap5\ActiveForm */

$this->title = Yii::t('app', 'ویرایش : {key}', [
    'key' => $model->key,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ مدیریت سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-4">
            <?php
            if ($model->key == 'logo' || $model->key == 'favicon' || $model->key == 'map'){
                echo $form->field($model, 'file')->fileInput();
            }else{
                echo $form->field($model, 'content')->textInput(['maxlength' => true]);
            }
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
