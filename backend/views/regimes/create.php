<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Regimes $model */

$this->title = Yii::t('app', 'افزودن برنامه جدید');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست برنامه ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="regimes-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
