<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Gallery $model */

$this->title = Yii::t('app', 'افزودن عکس');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ گالری'), 'url' => ['index', 'parent_id' => $model->parent_id, 'belong' => $model->belong]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="gallery-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
