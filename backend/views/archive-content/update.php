<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ArchiveContent $model */

$this->title = Yii::t('app', 'ویرایش فایل');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archive Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>
<div class="archive-content-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
