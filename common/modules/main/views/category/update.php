<?php

use common\modules\main\models\Category;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Category */
/* @var $parents_tree common\modules\main\controllers\CategoryController */

if ($model->belong == Category::BELONG_BLOG) {
    $breadcrumbs = 'لیست دست‌های بلاگ';
}elseif ($model->belong == Category::BELONG_JOB) {
    $breadcrumbs = 'لیست مشاغل';
}elseif ($model->belong == Category::BELONG_TAG){
    $breadcrumbs = 'برچسب ها';
}elseif ($model->belong == Category::BELONG_ARCHIVE){
    $breadcrumbs = 'دسته بندی آرشیو ها';
}else {
    $breadcrumbs = 'لیست دسته‌ها';
}

$this->title = Yii::t('app', 'ویرایش : {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ '.$breadcrumbs), 'url' => ['index', 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'parents_tree' => $parents_tree,
    ]) ?>

</div>
