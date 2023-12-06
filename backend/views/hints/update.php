<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Hints $model */
/** @var common\models\Packages $packages */


$this->title = Yii::t('app', 'ویرایش: {title}', [
    'title' => $model->title,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ اعلانات'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="hints-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'packages'=>$packages,

    ]) ?>

</div>
