<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Archives $model */

$this->title = Yii::t('app', 'افزودن آرشیو');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Archives'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="archives-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
