<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Comments $model */

$this->title = Yii::t('app', 'ویرایش');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ نظرات'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="comments-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
