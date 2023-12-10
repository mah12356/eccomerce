<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\models\Result $model */

$this->title = Yii::t('app', 'Create Result');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Results'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="result-create">

    <h1>ساخت یک نتیجه</h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
