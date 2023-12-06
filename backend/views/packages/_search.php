<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\PackagesSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="packages-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'coach_id') ?>

    <?= $form->field($model, 'category') ?>

    <?= $form->field($model, 'name') ?>

    <?= $form->field($model, 'discount') ?>

    <?php // echo $form->field($model, 'description') ?>

    <?php // echo $form->field($model, 'start_register') ?>

    <?php // echo $form->field($model, 'end_register') ?>

    <?php // echo $form->field($model, 'start_date') ?>

    <?php // echo $form->field($model, 'period') ?>

    <?php // echo $form->field($model, 'poster') ?>

    <?php // echo $form->field($model, 'alt') ?>

    <?php // echo $form->field($model, 'status') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
