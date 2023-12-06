<?php

use common\modules\blog\models\Gallery;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Gallery $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="gallery-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-4">
            <?= $form->field($model, 'type')->dropDownList(Gallery::getType(),
                [
                    'onClick' => 'previewInput(this.value)',
                    'prompt' => 'لطفا انتخاب کنید',
                ]) ?>
        </div>
        <div class="col-3"><?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?></div>
    </div>


    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
    function previewInput(value) {
        let
    }
</script>
