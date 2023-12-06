<?php

use common\modules\blog\models\Articles;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use dosamigos\tinymce\TinyMce;


/** @var yii\web\View $this */
/** @var common\modules\blog\models\Articles $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-3"><?= $form->field($model, 'category_id')->dropDownList(Articles::getCategory(), ['prompt' => 'لطفا انتخاب کنید']) ?></div>
    </div>
    <div class="row">
        <div class="col-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
        <div class="col-4"><?= $form->field($model, 'page_title')->textInput(['maxlength' => true]) ?></div>
    </div>
    <?= $form->field($model, 'introduction')->widget(TinyMce::className(), [
        'options' => ['rows' => 1],
        'language' => 'fa',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste",
                "link",
                "table"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | link | hyperlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | table"
        ]
    ]); ?>

    <div class="row">
        <div class="col-4">
            <div class="alert alert-warning" role="alert">
                <p><b>ابعاد : مربعی</b></p>
                <p>پیشنهادی : 400px * 400px</p>
            </div>
            <?= $form->field($model, 'posterFile')->fileInput() ?>
        </div>
        <div class="col-4">
            <div class="alert alert-warning" role="alert">
                <p><b>ابعاد : مستطیلی</b></p>
                <p>1300px * 400px</p>
            </div>
            <?= $form->field($model, 'imageFile')->fileInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'videoFile')->fileInput() ?></div>
        <div class="col-8"><?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
