<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\modules\models\Demos $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="demos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'for')->dropDownList(['client_comments'=>'برای نظرات دختران مهسا','demo_class'=>'برای دموی کلاس ها','ad_sentence'=>'ویدیو متن تبلیغاتی'],['prompt'=>'انتخاب کنید'])?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ذخیره'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
