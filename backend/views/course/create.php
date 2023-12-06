<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Course $model */

$this->title = Yii::t('app', 'افزودن دوره');
$this->params['breadcrumbs'][] = ['label' => '/ لیست پکیج ها', 'url' => ['/packages/index']];
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'لیست دوره ها'), 'url' => ['index', 'package_id' => $model->package_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
