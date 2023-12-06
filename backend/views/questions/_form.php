<?php

use common\models\Questions;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Url;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Questions $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->registerJs('
    $(document).ready(function(){
        previewOptions(document.getElementById("questions-type").value);
        $.get("'. Url::toRoute('/questions/set-option') .'", 
            { content : "" }
        ).done(
            function(data){
                $("#option-list").html(data);
            }
        )
    });
    function previewOptions(value) {
        let optionSection = document.getElementById("option-section");
        if (value != "'. Questions::TYPE_TEXT .'" && value != "'. Questions::TYPE_NUMBER .'") {
            optionSection.style.display = "block";
        }else {
            optionSection.style.display = "none";
        }
        console.log(optionSection);
    }
', View::POS_END)
?>

<div class="questions-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'type')->dropDownList(Questions::getType(), [
                'prompt' => 'لطفا انتخاب کنید',
                'onChange' => 'previewOptions(this.value)',
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-1"><?= $form->field($model, 'required')->checkbox() ?></div>
    </div>

    <div id="option-section" class="row my-5" style="display: none">
        <p><b>مورد های تعریف شده</b></p>
        <div id="option-list"></div>

        <div class="col-md-4">
            <input id="option" class="form-control form-control d-inline w-50" type="text" placeholder="تعریف مورد" aria-label=".form-control-sm example">
            <?= Html::button('ثبت', [
                'class' => 'btn btn-primary d-inline mx-3',
                'onclick' => '
                    $.get("'. Url::toRoute('/questions/set-option') .'", 
                        { content : $("#option").val() }
                    ).done(
                        function(data){
                            document.getElementById("option").value = "";
                            $("#option-list").html(data);
                        }
                    )
                ',
            ]); ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>

</script>