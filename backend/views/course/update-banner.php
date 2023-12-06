<?php

use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Course $model */

$this->title = Yii::t('app', 'ویرایش : {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => '/ لیست پکیج ها', 'url' => ['/packages/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'لیست دوره ها'), 'url' => ['index', 'package_id' => $model->package_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'posterFile')->fileInput() ?>
     <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    <?php ActiveForm::end(); ?>


</div>
