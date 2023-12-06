<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Media $model */

$this->title = Yii::t('app', 'ویرایش');

$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ مدیا'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="media-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
