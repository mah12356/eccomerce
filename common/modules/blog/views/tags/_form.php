<?php

use common\modules\main\models\Category;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Tags $model */
/** @var yii\bootstrap5\ActiveForm $form */

$tags = ArrayHelper::map(Category::find()->where(['belong' => Category::BELONG_TAG])->asArray()->all(), 'id', 'title');
?>

<div class="tags-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="col-4">
        <?= $form->field($model, 'tag_id')->dropDownList($tags, ['prompt' => 'لطفا انتخاب کنید']) ?>
    </div>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'ثبت اطلاعات'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
