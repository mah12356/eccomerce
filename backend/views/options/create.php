<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Options $model */

$this->title = Yii::t('app', 'افزودن گزینه');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست گزینه ها'), 'url' => ['index', 'question_id' => $model->question_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="options-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
