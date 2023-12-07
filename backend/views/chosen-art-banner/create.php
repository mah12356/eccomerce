<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var backend\models\ChosenArtBanner $model */

$this->title = Yii::t('app', 'انخاب بنر');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'انتخاب بنر'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="chosen-art-banner-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
