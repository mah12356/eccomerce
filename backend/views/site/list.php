<?php

/** @var $model common\models\User */
/** @var $searchModel common\models\RegisterSearch */
/** @var $dataProvider yii\data\ActiveDataProvider */

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
use yii\grid\GridView;

$this->title = Yii::t('app', 'لیست شاگردان');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ انتخاب پکیج و دوره'), 'url' => ['/site/finance']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(); ?>

<div class="row">
    <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'name')->textInput() ?></div>
    <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'lastname')->textInput() ?></div>
    <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'mobile')->textInput() ?></div>
</div>
<div class="row">
    <div class="col-md-4 col-12">
        <div class="form-group" id="courses"></div>
    </div>
</div>

<div class="form-group my-3">
    <?= Html::submitButton(Yii::t('app', 'جستجو'), ['class' => 'btn btn-success']) ?>
    <?= Html::a(Yii::t('app', 'همه نتایج'), ['/site/list'], ['class' => 'btn btn-secondary']) ?>
</div>

<?php ActiveForm::end(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
//        ['class' => 'yii\grid\SerialColumn'],

        [
            'label' => 'شناسه کاربری',
            'format' => 'html',
            'content' => function ($model) {
                return '<p>'. $model->user->id .'</p>';
            }
        ],
        [
            'label' => 'نام',
            'format' => 'html',
            'content' => function ($model) {
                return '<p>'. $model->user->name .'</p>';
            }
        ],
        [
            'label' => 'نام خانوادگی',
            'format' => 'html',
            'content' => function ($model) {
                return '<p>'. $model->user->lastname .'</p>';
            }
        ],
        [
            'label' => 'شماره موبایل',
            'format' => 'html',
            'content' => function ($model) {
                return '<p>'. $model->user->mobile .'</p>';
            }
        ],
        [
            'label' => 'تعداد پکیج‌های ثبت نامی',
            'format' => 'html',
            'content' => function ($model) {
                return '<p>'. count($model->user->enroll) .'</p>';
            }
        ],
    ],
]); ?>

