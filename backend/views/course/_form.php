<?php

use common\models\Course;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Course $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->registerJs("
    $(document).ready(function() {
        let timeFrames = document.getElementById('counter');
        if ($('#course-belong').val()) {
            if ($('#course-belong').val() !== '". Course::BELONG_DIET ."' && $('#course-belong').val() !== '". Course::BELONG_SHOCK_DIET ."') {
               timeFrames.style.display = 'none';
            }else {
                timeFrames.style.display = 'block';
            }
        }
    });
    
    function changeDisplay(belong) {
        let timeFrames = document.getElementById('counter');
        if (belong !== '". Course::BELONG_DIET ."' && belong !== '". Course::BELONG_SHOCK_DIET ."') {
            timeFrames.style.display = 'none';
        }else {
            timeFrames.style.display = 'block';
        }
    }
", View::POS_END)
?>

<div class="course-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'belong')->dropDownList(Course::getBelong(), [
                'prompt' => 'لطفا انتخاب کنید',
                'onChange' => 'changeDisplay(this.value)',
            ]) ?>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'price')->textInput() ?></div>
        <div id="counter" class="col-md-2" style="display: none"><?= $form->field($model, 'count')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-md-1"><?= $form->field($model, 'required')->checkbox() ?></div>
    </div>

    <?= $form->field($model, 'description')->textarea(['rows' => 8]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
