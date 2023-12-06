<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Faq $model */

$this->title = Yii::t('app', 'ثبت سوال جدید');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ سوالات متداول'), 'url' => ['index', 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="faq-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
