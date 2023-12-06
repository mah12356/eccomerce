<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\main\models\MetaTags $model */

$this->title = Yii::t('app', 'ویرایش : {content}', [
    'content' => $model->content,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ متا تگ ها'), 'url' => ['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meta-tag-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
