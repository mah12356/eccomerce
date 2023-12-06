<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Sections $model */

$this->title = Yii::t('app', 'ویرایش : {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست جلسات'), 'url' => ['index', 'group' => $model->group]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="sections-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
