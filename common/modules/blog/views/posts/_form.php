<?php

use common\modules\blog\models\Posts;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use dosamigos\tinymce\TinyMce;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Posts $model */
/** @var yii\bootstrap5\ActiveForm $form */

?>

<div class="posts-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->widget(TinyMce::className(), [
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

    <?= $form->field($model, 'text')->widget(TinyMce::className(), [
        'options' => ['rows' => 6],
        'language' => 'fa',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste",
                "link"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | link | hyperlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        ]
    ]); ?>

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'order')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'uploadFile')->fileInput() ?></div>
        <div class="col-md-6"><?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'btn_text')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-3"><?= $form->field($model, 'btn_link')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'tip')->widget(TinyMce::className(), [
        'options' => ['rows' => 2],
        'language' => 'fa',
        'clientOptions' => [
            'plugins' => [
                "advlist autolink lists link charmap print preview anchor",
                "searchreplace visualblocks code fullscreen",
                "insertdatetime media table contextmenu paste",
                "link"
            ],
            'toolbar' => "undo redo | styleselect | bold italic | link | hyperlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
        ]
    ]);?>


    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
