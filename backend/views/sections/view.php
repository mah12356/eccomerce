<?php

use common\components\Gadget;
use common\components\Jdf;
use yii\helpers\Html;
use yii\web\YiiAsset;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Sections $model */
/** @var common\models\SectionContents $content */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست جلسات'), 'url' => ['index', 'group' => $model->group]];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);
?>
<div class="sections-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'title',
            [
                'label' => 'تاریخ شروع جلسه',
                'value' => Jdf::jdate('Y/m/d', $model->date),
            ],
            [
                'label' => 'ساعت شروع جلسه',
                'value' => Jdf::jdate('H:i', $model->start_at),
            ],
            [
                'label' => 'ساعت پایان جلسه',
                'value' => Jdf::jdate('H:i', $model->end_at),
            ],
        ],
    ]) ?>
</div>

<?php if ($content) { ?>

<div class="container-fluid">
    <div class="row">
        <?php foreach ($content as $item) { ?>
            <div class="col-6 mb-4">
                <video class="w-auto h-auto d-block mx-auto" controls>
                    <source src="<?= Gadget::showFile($item['file'], 'sections/' . $model->group) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            </div>
        <?php } ?>
    </div>
</div>

<?php }
