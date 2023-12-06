<?php

use dosamigos\tinymce\TinyMce;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Packages $model */
/** @var common\modules\main\models\Category $category */
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

<div class="packages-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'category')->dropDownList(ArrayHelper::map($category, 'id', 'title'), ['prompt' => 'لطفا انتخاب کنید']) ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'discount')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <div class="mb-3 field-packages-end_register required">
                <label for="start_register" class="form-label">تاریخ شروع ثبت نام</label>
                <input type="text" data-jdp class="form-control" id="start_register" name="Packages[start_register]" value="<?= $model->start_register ?>">
            </div>
        </div>
        <div class="col-2">
            <div class="mb-3 field-packages-end_register required">
                <label for="end_register" class="form-label">تاریخ پایان ثبت نام</label>
                <input type="text" data-jdp class="form-control" id="end_register" name="Packages[end_register]" value="<?= $model->end_register ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-2">
            <div class="mb-3 field-packages-end_register required">
                <label for="end_register" class="form-label">تاریخ شروع کلاس ها</label>
                <input type="text" data-jdp class="form-control" id="end_register" name="Packages[start_date]" value="<?= $model->start_date ?>">
            </div>
        </div>
        <div class="col-2"><?= $form->field($model, 'period')->textInput() ?></div>
    </div>

    <?= $form->field($model, 'motive')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->widget(TinyMce::className(), [
        'options' => ['rows' => 8   ],
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
        <div class="col-md-4"><?= $form->field($model, 'posterFile')->fileInput() ?></div>
        <div class="col-md-4"><?= $form->field($model, 'videoFile')->fileInput() ?></div>
        <div class="col-md-12"><?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
