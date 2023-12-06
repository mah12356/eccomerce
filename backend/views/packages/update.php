<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Packages $model */
/** @var common\modules\main\models\Category $category */

$this->title = Yii::t('app', ' ویرایش : {name}', [
    'name' => $model->name,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست پکیج ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="packages-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'category' => $category,
    ]) ?>

</div>
