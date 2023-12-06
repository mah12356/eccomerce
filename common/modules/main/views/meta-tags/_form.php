<?php

use common\modules\main\models\MetaTags;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\MetaTags */
/* @var $form yii\bootstrap5\ActiveForm */

?>

<div class="meta-tag-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'type')->dropDownList((array)MetaTags::getType(), ['prompt' => 'لطفا انتخاب کنید']) ?></div>
        <div class="col-4"><?= $form->field($model, 'content')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
