<?php

use common\modules\main\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use dosamigos\tinymce\TinyMce;

/** @var yii\web\View $this */
/** @var common\models\Archives $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="archives-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'category_id')->dropDownList(
                ArrayHelper::map(Category::find()->where(['belong' => Category::BELONG_ARCHIVE])->asArray()->all(), 'id', 'title'),
                ['prompt' => 'لطفا انتخاب کنید']
            ) ?>
        </div>
        <div class="col-md-4"></div>
    </div>

    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-4"><?= $form->field($model, 'subject')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'intro')->textInput(['maxlength' => true]) ?>

        <?= $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 1],
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

    <div class="row">
        <div class="col-md-3"><?= $form->field($model, 'btn')->textInput(['maxlength' => true]) ?></div>
        <div class="col-md-6"><?= $form->field($model, 'link')->textInput(['maxlength' => true]) ?></div>
    </div>

    <?= $form->field($model, 'imageFile')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
