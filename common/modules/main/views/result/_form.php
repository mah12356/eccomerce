<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\modules\models\Result $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="result-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'skill')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ذخیره'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
