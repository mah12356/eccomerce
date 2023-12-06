<?php

use common\components\Gadget;
use common\components\Jdf;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\CourseSections $model */
/** @var common\models\Groups $groups */
/** @var yii\bootstrap5\ActiveForm $form */
?>

<div class="course-sections-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'section_group')->dropDownList(ArrayHelper::map($groups, 'id', 'title'), ['prompt' => 'لطفا انتخاب کنید']) ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
