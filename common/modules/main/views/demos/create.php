<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\modules\models\Demos $model */

$this->title = Yii::t('app', 'ساخت ویدیو و توضیحات');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Demos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="demos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
