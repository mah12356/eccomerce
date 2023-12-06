<?php
/** @var yii\web\View $this */
/** @var common\models\Sections $model */
/** @var yii\bootstrap5\ActiveForm $form */

use common\models\Groups;
use common\models\Sections;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->registerCssFile('@web/css/date-picker.css');
$this->registerJsFile('@web/js/date-picker.js');

$this->registerJs('
jalaliDatepicker.startWatch({
  minDate: "attr",
  maxDate: "attr"
}); 
', View::POS_END);

$this->title = Yii::t('app', 'افزودن گروه جلسات');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست گروه جلسات'), 'url' => ['/sections/groups']];
$this->params['breadcrumbs'][] = $this->title;

$days = [
    'شنبه' => 'شنبه',
    'یکشنبه' => 'یکشنبه',
    'دوشنبه' => 'دوشنبه',
    'سه شنبه' => 'سه شنبه',
    'چهارشنبه' => 'چهارشنبه',
    'پنجشنبه' => 'پنجشنبه',
    'جمعه' => 'جمعه',
];
?>

<div class="sections-form">


    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>


    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'title')->textInput() ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'type')->dropDownList(Groups::getType(), ['prompt' => 'لطفا انتخاب کنید']) ?></div>
        <div class="col-2">
            <div class="mb-3 field-packages-end_register required">
                <label for="start_register" class="form-label">تاریخ شروع جلسات</label>
                <input type="text" data-jdp class="form-control" id="start_register" name="Groups[start_date]" value="<?= $model->start_date ?>">
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'start_time')->textInput(['type' => 'time']) ?></div>
        <div class="col-md-2"><?= $form->field($model, 'finish_time')->textInput(['type' => 'time']) ?></div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'sections_count')->textInput() ?></div>
    </div>

    <div class="row">
        <div class="col-md-2"><?= $form->field($model, 'interval')->checkboxList($days) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
