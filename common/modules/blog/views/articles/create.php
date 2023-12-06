<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Articles $model */

$this->title = Yii::t('app', 'ساخت مقاله جدید');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', ' / لیست مقالات'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="articles-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
