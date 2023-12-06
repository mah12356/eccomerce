<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Posts $model */

$this->title = Yii::t('app', 'افزودن متن');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست نوشته‌ها'), 'url' => ['index', 'page_id' => $model->page_id, 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="posts-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
