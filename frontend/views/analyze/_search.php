<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\AnalyzeSearch $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="analyze-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'user_id') ?>

    <?= $form->field($model, 'register_id') ?>

    <?= $form->field($model, 'package_id') ?>

    <?= $form->field($model, 'course_id') ?>

    <?php // echo $form->field($model, 'height') ?>

    <?php // echo $form->field($model, 'weight') ?>

    <?php // echo $form->field($model, 'age') ?>

    <?php // echo $form->field($model, 'gender') ?>

    <?php // echo $form->field($model, 'wrist') ?>

    <?php // echo $form->field($model, 'arm') ?>

    <?php // echo $form->field($model, 'chest') ?>

    <?php // echo $form->field($model, 'under_chest') ?>

    <?php // echo $form->field($model, 'belly') ?>

    <?php // echo $form->field($model, 'waist') ?>

    <?php // echo $form->field($model, 'hip') ?>

    <?php // echo $form->field($model, 'thigh') ?>

    <?php // echo $form->field($model, 'shin') ?>

    <?php // echo $form->field($model, 'front_image') ?>

    <?php // echo $form->field($model, 'side_image') ?>

    <?php // echo $form->field($model, 'back_image') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'date') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
