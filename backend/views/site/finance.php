<?php

/** @var $packages common\models\Packages */
/** @var $model common\models\Register */

use yii\bootstrap5\ActiveForm;
use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'انتخاب پکیج و دوره';
$this->params['breadcrumbs'][] = ' / ' . $this->title;

$this->registerJs('
    function findCourses(id) {
        $.get("' . Url::toRoute('/site/get-all-courses') . '", 
            { id : id }
        ).done(
            function(data){
                $("#courses").html(data);
            }
        )
    }
', View::POS_END)
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-lg-3 col-md-6 col-12">
        <?= $form->field($model, 'package_id')->dropDownList(ArrayHelper::map($packages, 'id', 'name'),
            ['prompt' => 'لطفا انتخاب کنید', 'onchange' => 'findCourses(this.value)']) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-4 col-12">
        <div class="form-group" id="courses"></div>
    </div>
</div>

<div class="form-group mt-3">
    <?= Html::submitButton(Yii::t('app', 'نمایش لیست'), ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>
