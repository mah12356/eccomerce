<?php

use common\components\Gadget;
use common\components\Jdf;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Sections $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->registerCssFile('@web/css/date-picker.css');
$this->registerJsFile('@web/js/date-picker.js');

$this->registerJs('
jalaliDatepicker.startWatch({
  minDate: "attr",
  maxDate: "attr"
}); 
', View::POS_END)
?>

<div class="sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <div class="field-packages-date required">
                <label for="date" class="form-label">تاریخ برگزاری کلاس</label>
                <input type="text" data-jdp class="form-control" id="date" name="Sections[date]" value="<?= Gadget::convertToEnglish(Jdf::jdate('Y/m/d', (string)$model->date)) ?>">
            </div>
        </div>
        <div class="col-md-2"><?= $form->field($model, 'start_at')->textInput(['type' => 'time']) ?></div>
        <div class="col-md-2"><?= $form->field($model, 'end_at')->textInput(['type' => 'time']) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
