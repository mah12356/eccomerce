<?php
/** @var common\models\SignupForm $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'ثبت نام کاربر';
?>

<h1><?= $this->title ?></h1>
<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3 col-12">
        <?= $form->field($model, 'name')->textInput(['autofocus' => true]) ?>
    </div>
    <div class="col-md-3 col-12">
        <?= $form->field($model, 'lastname')->textInput() ?>
    </div>
</div>

<div class="form-group mt-3">
    <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
