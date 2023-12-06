<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Config */
/* @var $form yii\bootstrap5\ActiveForm */

$this->title = Yii::t('app', 'افزودن کلیدواژه');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ مدیریت سایت'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="config-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'key')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
