<?php

use common\models\Regimes;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Regimes $model */

?>

<div class="regimes-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'type')->dropDownList(Regimes::getType(), ['prompt' => 'لطفا انتخاب کنید']) ?></div>
        <div class="col-md-2"><?= $form->field($model, 'calorie')->textInput(['type' => 'number', 'min' => 0]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'uploadFile')->fileInput() ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
