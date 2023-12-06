<?php

use common\models\Hints;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Hints $model */
/** @var yii\bootstrap5\ActiveForm $form */
/** @var common\models\Packages $packages */

?>

<div class="hints-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-3">
            <?= $form->field($model, 'type')->dropDownList(Hints::getType(), ['prompt' => 'لطفا انتخاب کنید']) ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'belong')->dropDownList(Hints::getBelong(), ['prompt' => 'لطفا انتخاب کنید']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-3">
            <?= $form->field($model, 'typePackage')->dropDownList($packages, ['prompt' => 'عمومی']) ?>
        </div>
        <div class="col-3">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-9"><?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
