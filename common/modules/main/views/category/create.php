<?php

use common\modules\main\models\Category;
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\modules\main\models\Category */
/* @var $parents_tree common\modules\main\controllers\CategoryController */

if ($model->belong == Category::BELONG_BLOG) {
    $title = 'افزودن دسته بلاگ';
    $breadcrumbs = 'لیست دسته‌های بلاگ';
}elseif ($model->belong == Category::BELONG_PRODUCT){
    $title = 'دسته‌بندی محصولات';
    $breadcrumbs = 'لیست دسته‌ها';
}elseif ($model->belong == Category::BELONG_JOB) {
    $title = 'لیست مشاغل';
    $breadcrumbs = 'افزودن شغل';
}elseif ($model->belong == Category::BELONG_TAG) {
    $title = 'افزودن برچسب';
    $breadcrumbs = 'افزودن برچسب';
}elseif ($model->belong == Category::BELONG_ARCHIVE) {
    $title = 'افزودن دسته آرشیو';
    $breadcrumbs = 'افزودن برچسب';
}else{
    $title = '';
    $breadcrumbs = '';
}

$this->title = Yii::t('app', $title);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ '.$breadcrumbs), 'url' => ['index', 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'parents_tree' => $parents_tree,
    ]) ?>

</div>
