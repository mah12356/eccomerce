<?php

use common\components\Gadget;
use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Coach $model */

$this->title = $model->user->name . ' ' . $model->user->lastname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Coaches'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="coach-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-info']) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'instagram_id',
            'bio',
            'phone',
            'description:ntext',
        ],
    ]) ?>

    <div class="row m4-5">
        <div class="col-4">
            <p><b>پروفایل</b></p>
            <img class="w-100" src="<?= Gadget::showFile($model->avatar, 'coach') ?>" alt="">
        </div>
        <div class="col-4">
            <p><b>پستر</b></p>
            <img class="w-100" src="<?= Gadget::showFile($model->poster, 'coach') ?>" alt="">
        </div>
    </div>
</div>
