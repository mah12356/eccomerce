<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sections $model */

$this->title = Yii::t('app', 'بارگذاری فایل جلسه : {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست جلسات'), 'url' => ['index', 'group' => $model->group]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4"><?= $form->field($model, 'file')->textInput(['maxlength' => true])->label('نام فایل') ?></div>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
