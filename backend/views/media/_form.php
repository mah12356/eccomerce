<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Media $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="media-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'caption')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
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
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'posterFile')->fileInput() ?>
        </div>
        <div class="col-md-6 col-12">
            <?= $form->field($model, 'fileInput')->fileInput() ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <div class="row">
        <div class="col-md-6 col-12">
            <?php

            if ($model->poster != null) {
                ?>
                <img src="<?= \common\components\Gadget::showFile($model->poster, \common\models\Media::UPLOAD_PATH) ?>" class="card-img-top" alt="...">
                <?php
            }
            ?>
        </div>
        <div class="col-md-6 col-12">
            <?php
            if ($model->file != null) {
                ?>
                <video class="w-100 h-auto" controls poster="<?= \common\components\Gadget::showFile($model->poster, \common\models\Media::UPLOAD_PATH) ?>">
                    <source src="<?= \common\components\Gadget::showFile($model->file, \common\models\Media::UPLOAD_PATH) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php
            }
            ?>
        </div>
    </div>

</div>
