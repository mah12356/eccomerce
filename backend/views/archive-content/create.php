<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\ArchiveContent $model */

$this->title = Yii::t('app', 'افزودن فایل');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archive Contents'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archive-content-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
