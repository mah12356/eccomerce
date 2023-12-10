<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\models\PackageDesign $model */

$this->title = Yii::t('app', 'ساخت دلیل');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Package Designs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="package-design-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
