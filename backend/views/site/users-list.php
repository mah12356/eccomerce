<?php

/** @var $searchModel common\models\UserSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */
/** @var $packages common\models\Packages */
/** @var $model common\models\Register */

use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

$this->title = 'ثبت نامی‌های جدید';

?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-lg-3 col-md-6 col-12">
        <?= $form->field($model, 'package_id')->dropDownList(ArrayHelper::map($packages, 'id', 'name'), ['prompt' => 'لطفا انتخاب کنید']) ?>
    </div>
</div>
<div class="form-group mt-3">
    <?= Html::submitButton(Yii::t('app', 'جستجو'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>


<div>
    <?php if (isset($_SESSION['usersList']) && $_SESSION['usersList']) {
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                'lastname',
                'mobile',
            ],
        ]);
    } ?>
</div>