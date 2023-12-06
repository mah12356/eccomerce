<?php
/** @var common\models\User $model */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->title = 'جستجو کاربر';
?>

<h1><?= $this->title ?></h1>
<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-md-3 col-12">
        <?= $form->field($model, 'mobile')->textInput(['autofocus' => true]) ?>
    </div>
</div>

<div class="form-group mt-3">
    <?= Html::submitButton(Yii::t('app', 'جستجو'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
