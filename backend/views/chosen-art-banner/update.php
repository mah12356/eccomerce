<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ChosenArtBanner $model */

$this->title = Yii::t('app', 'انتخاب بنر دیگر: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'انخاب بنر'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'بروز رسانی');
?>
<div class="chosen-art-banner-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
