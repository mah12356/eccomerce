<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Questions $model */

$this->title = Yii::t('app', 'تعریف سوال');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست سوالات'), 'url' => ['index', 'course_id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="questions-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
