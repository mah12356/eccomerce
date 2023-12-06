<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */

$this->title = Yii::t('app', 'پاسخ به سوالات آنالیز');
?>
<div class="analyze-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>