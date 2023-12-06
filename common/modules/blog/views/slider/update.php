<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\blog\models\Slider $model */

$this->title = Yii::t('app', 'ویرایش اسلایدر');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ اسلایدر‌ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="slider-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
