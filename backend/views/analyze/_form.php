<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="analyze-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'user_id')->textInput() ?>

    <?= $form->field($model, 'register_id')->textInput() ?>

    <?= $form->field($model, 'package_id')->textInput() ?>

    <?= $form->field($model, 'course_id')->textInput() ?>

    <?= $form->field($model, 'height')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'age')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'gender')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'wrist')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'arm')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'chest')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'under_chest')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'belly')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'waist')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'hip')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'thigh')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'shin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'front_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'side_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'back_image')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'updated_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
