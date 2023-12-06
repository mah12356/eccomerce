<?php

use yii\helpers\Html;
use yii\web\View;
use yii\bootstrap5\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Category */
/* @var $parents_tree common\modules\main\controllers\CategoryController */
/* @var $form yii\bootstrap5\ActiveForm */

$this->registerCssFile('@web/css/tree_view.css');
$this->registerJsFile(
    '@web/js/tree_view.js',
    ['position' => View::POS_END]
);
?>

<div class="category-form">

    <?php $form = ActiveForm::begin(); ?>


    <?= $parents_tree ?>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?></div>
    </div>

    <div class="row">
        <div class="col-4"><?= $form->field($model, 'imageFile')->fileInput() ?></div>
    </div>

    <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
