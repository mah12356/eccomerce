<?php

use common\components\Jdf;
use common\modules\blog\models\Articles;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;
use dosamigos\tinymce\TinyMce;


/** @var yii\web\View $this */
/** @var common\modules\blog\models\Articles $model */
/** @var yii\widgets\ActiveForm $form */


$this->title = Yii::t('app', 'ویرایش تاریخ نشر');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ' / لیست مقالات'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->registerCssFile('@web/css/date-picker.css');
$this->registerJsFile('@web/js/date-picker.js');

$this->registerJs('
jalaliDatepicker.startWatch({
  minDate: "attr",
  maxDate: "attr"
}); 
', \yii\web\View::POS_END)
?>

<h1><?= Html::encode($this->title) ?></h1>

<div class="articles-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-2">
            <div class="mb-3 field-articles-modify_date required">
                <label for="modify_date" class="form-label">تاریخ نشر</label>
                <input type="text" data-jdp class="form-control" id="modify_date" name="Articles[modify_date]" value="<?= \common\components\Gadget::convertToEnglish(Jdf::jdate('Y/m/d', $model->modify_date)) ?>">
            </div>
        </div>
        <div class="col-md-2"><?= $form->field($model, 'time')->textInput(['type' => 'time']) ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
