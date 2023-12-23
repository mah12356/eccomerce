<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var backend\models\ChosenArtBanner $model */
/** @var yii\widgets\ActiveForm $form */

////// site.css
?>

<div class="chosen-art-banner-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <?php
    echo $form->field($model, 'for')->dropDownList(['chosen_art' => 'برای مقالات برگزیده', 'last_arts' => 'برای آخرین مقالات']);
    ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ذخیره'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<section class="row">
    <article class="col-6 xy">
        <p>طول : ۲۲۰ پیکسل</p>
        <p>عرض : ۱۱۰ پیکسل</p>
    </article>
</section>