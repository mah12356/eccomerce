<?php

use common\models\Analyze;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */
/** @var yii\bootstrap5\ActiveForm $form */

$this->registerjs(' $(document).ready(function() {
        $(document).on("change", ".file-upload input[type='. 'file' .']", function() {
            var filename = $(this).val();
            if (/^\s*$/.test(filename)) {
                $(this).parents(".file-upload").removeClass("upload");
                $(this).parents(".file-upload").find(".file-select-name").text("No file chosen...");
            } else {
                $(this).parents(".file-upload").addClass("upload");
                $(this).parents(".file-upload").find(".file-select-name").text(filename.substring(filename.lastIndexOf() + 1, filename.length));
            }
            var uploadFile = $(this);
            var files = !!this.files ? this.files : [];
            if (!files.length || !window.FileReader) return; // no file selected, or no FileReader support

            if (/^image/.test(files[0].type)) { // only image file
                var reader = new FileReader(); // instance of the FileReader
                reader.readAsDataURL(files[0]); // read the local file

                reader.onloadend = function() { // set image data as background of div
                    uploadFile.closest(".file-upload").find(".imagePreview").css({"background-image": "url(" + this.result + ")", "z-index": "2"});
                }
            }
        });
    });', View::POS_END);
?>

<style>
    .imagePreview {
        width: 100%;
        max-width: 125px;
        height: 100%;
        max-height: 125px;
        background-size: cover;
        background-repeat: no-repeat;
        margin-right: 0;
        position: absolute;
        background-color: #fff;
        top: 0;
        left: 0;
    }
    .file-upload{
        display: block;
    }

    .file-select {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }
    .file-select.file-select-box {
        width: 125px;
        height: 125px;
        display: inline-block;
        border-radius: 14px;
    }
    .file-upload-custom-btn {
        width: 125px;
        height: 125px;
        border: none;
        background-color: rgba(82, 33, 189, 0.15);
        color: #5221BD;
        font-size: 30px;
        z-index: 1;
        position: relative;
    }
    .file-upload-custom-btn span {
        display: block;
        text-align: center;
        font-size: 15px;
    }
    .file-select-name{
        margin-left: 15px;
    }
    .file-select input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
    }

    .file-select.file-select-box input[type=file]{
        z-index: 2;
    }

    .file-upload + .file-upload{
        margin-left: 10px;
    }
</style>

<div class="col-lg-5 col-md-10 col-12 d-block mx-auto">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]) ?>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-12"><?= $form->field($model, 'gender')->dropDownList(Analyze::getGender()) ?></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'age')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'wrist')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'arm')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'chest')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'under_chest')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'belly')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'waist')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'hip')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'thigh')->textInput(['maxlength' => true]) ?></div>
        <div class="col-lg-4 col-md-4 col-6"><?= $form->field($model, 'shin')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row mt-3">
        <div class="col-lg-4 col-md-4 col-12">
            <div class="file-upload mx-auto">
                <div class="file-select file-select-box">
                    <div class="imagePreview"></div>
                    <button class="file-upload-custom-btn">
                        <i class="fa fa-plus"></i>
                        <span>عکس از روبرو</span>
                    </button>
                    <input type="file" name="frontFile" class="profileimg">
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
            <div class="file-upload mx-auto">
                <div class="file-select file-select-box">
                    <div class="imagePreview"></div>
                    <button class="file-upload-custom-btn">
                        <i class="fa fa-plus"></i>
                        <span>عکس از کنار</span>
                    </button>
                    <input type="file" name="sideFile" class="profileimg">
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-12">
            <div class="file-upload mx-auto">
                <div class="file-select file-select-box">
                    <div class="imagePreview"></div>
                    <button class="file-upload-custom-btn">
                        <i class="fa fa-plus"></i>
                        <span>عکس از پشت</span>
                    </button>
                    <input type="file" name="backFile" class="profileimg">
                </div>
            </div>
        </div>
    </div>

    <div class="form-group mt-5">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<script>
    // -------------file-selection-with-image-and-name------------

</script>