<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */

$this->title = Yii::t('app', 'ویرایش اطلاعات');
?>
<div class="analyze-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
