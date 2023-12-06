<?php

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Gallery $model */

$this->title = Yii::t('app', 'افزودن عکس');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ گالری'), 'url' => ['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <div class="gallery-form">

        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-3"><?= $form->field($model, 'file')->fileInput(['maxlength' => true]) ?></div>
        </div>


        <?= $form->field($model, 'alt')->textInput(['maxlength' => true]) ?>

        <div class="form-group">
            <?= Html::submitButton(Yii::t('app', 'ثبت'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>