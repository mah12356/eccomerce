<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Community $model */

$this->title = Yii::t('app', 'پاسخ به نظر');
?>
<div class="community-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="community-form">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'reply')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'ثبت پاسخ'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>
