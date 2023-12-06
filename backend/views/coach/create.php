<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Coach $model */

$this->title = Yii::t('app', 'اطلاعات تکمیلی');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coaches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="coach-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
