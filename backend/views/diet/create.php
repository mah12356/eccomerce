<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Diet $model */

$this->title = Yii::t('app', 'Create Diet');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Diets'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="diet-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
