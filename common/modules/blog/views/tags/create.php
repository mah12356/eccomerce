<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Tags $model */

$this->title = Yii::t('app', 'افزودن برچسب');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Tags'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="tags-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
