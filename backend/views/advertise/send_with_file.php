<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="analyze-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'file')->fileInput() ?>

    <?= $form->field($model, 'massage')->textarea()->label("متن پیام") ?>



    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<div>
    <p class="text-danger fa-3x">توجه داشته باشید فایل با پسوند txt باشد و شماره ها هرکدام در یک خط فرار بگیرند</p>
</div>