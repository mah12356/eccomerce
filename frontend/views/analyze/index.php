<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Analyze;
use onmotion\apexcharts\ApexchartsWidget;
use yii\web\View;

/** @var yii\web\View $this */
/** @var common\models\Analyze $model */
/** @var frontend\controllers\AnalyzeController $weightPackage */
/** @var frontend\controllers\AnalyzeController $bellyWaist */

$this->title = Yii::t('app', 'آنالیز بدنی');

$this->registerCssFile('@web/css/factors.css');
$this->registerJsFile('@web/js/factors.js', [
    'position' => View::POS_END
]);

$url = Yii::$app->urlManager;
?>
<?php if ($model) { ?>
    <h1><?= $this->title ?></h1>
    <div class="container">
        <?php foreach ($model as $item) { ?>
            <div class="row factor-pane">
                <div class="col-md-4"><p class="factor-id py-3"><span>آنالیز <?= $item['package']['name'] ?></span></p>
                </div>
                <div class="col-md-3"><p class="m-0 py-3">
                        <span>تاریخ ثبت : </span><span><?= Jdf::jdate('Y/m/d', $item['date']) ?></span></p></div>
                <div class="col-md-3">
                    <?php if ($item['status'] == Analyze::STATUS_ACTIVE) { ?>
                        <p class="factor-status-pending m-0 py-3">در انتظار پاسخ</p>
                    <?php } else { ?>
                        <p class="factor-status-accept m-0 py-3">پاسخ داده شده</p>
                    <?php } ?>
                </div>
                <div class="col-md-2">
                    <button id="<?= $item['id'] ?>" class="accordion py-3">جزئیات</button>
                </div>
                <div id="panel_<?= $item['id'] ?>" class="panel">
                    <?php if ($item['status'] == Analyze::STATUS_ANSWERED) { ?>
                        <div class="container-fluid info-section py-3">
                            <div class="row">
                                <div class="col-md-3"><p class="subjects">قد : <span
                                            class="content"><?= Gadget::convertToPersian($item['height']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">وزن : <span
                                            class="content"><?= Gadget::convertToPersian($item['weight']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">سن : <span
                                            class="content"><?= Gadget::convertToPersian($item['age']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور مچ : <span
                                            class="content"><?= Gadget::convertToPersian($item['wrist']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور بازو : <span
                                            class="content"><?= Gadget::convertToPersian($item['arm']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور سینه : <span
                                            class="content"><?= Gadget::convertToPersian($item['chest']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور زیر سینه : <span
                                            class="content"><?= Gadget::convertToPersian($item['under_chest']) ?></span>
                                    </p></div>
                                <div class="col-md-3"><p class="subjects">دور شکم : <span
                                            class="content"><?= Gadget::convertToPersian($item['belly']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور کمر : <span
                                            class="content"><?= Gadget::convertToPersian($item['waist']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور باسن : <span
                                            class="content"><?= Gadget::convertToPersian($item['hip']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور ران : <span
                                            class="content"><?= Gadget::convertToPersian($item['thigh']) ?></span></p>
                                </div>
                                <div class="col-md-3"><p class="subjects">دور ساق : <span
                                            class="content"><?= Gadget::convertToPersian($item['shin']) ?></span></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <?php if ($item['status'] == Analyze::STATUS_ANSWERED) { ?>
                        <p class="mt-4">
                            <a class="btn btn-primary"
                               href="<?= $url->createUrl(['/analyze/update', 'id' => $item['id']]) ?>">ثبت</a>
                        </p>
                    <?php } else { ?>
                        <p class="mt-4">
                            <a class="btn btn-success"
                               href="<?= $url->createUrl(['/analyze/answer', 'id' => $item['id']]) ?>">پاسخ</a>
                        </p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>

<div class="row">
    <div class="col-lg-6 col-12">
        <?php if (count($weightPackage['categories']) > 1) { ?>
            <p class="text-center"><b>نمودار وزن به پکیج</b></p>
            <?= ApexchartsWidget::widget([
                'type' => 'line',
                'chartOptions' => [
                    'chart' => [
                        'toolbar' => [
                            'show' => false,
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => false, // Disable tooltips
                    ],
                    'xaxis' => [
                        'type' => 'category',
                        'categories' => $weightPackage['categories'],
                    ],
                    'plotOptions' => [
                        'bar' => [
                            'horizontal' => true,
                            'endingShape' => 'rounded'
                        ],
                    ],
                    'dataLabels' => [
                        'enabled' => true
                    ],
                    'stroke' => [
                        'show' => true,
                        'curve' => 'smooth',
                    ],
                ],
                'series' => $weightPackage['series']
            ]); ?>
        <?php } ?>
    </div>
    <div class="col-lg-6 col-12">
        <?php if (count($bellyWaist['categories']) > 1) { ?>
            <p class="text-center"><b>نمودار دور کمر به دور شکم</b></p>
            <?= ApexchartsWidget::widget([
                'type' => 'line',
                'chartOptions' => [
                    'chart' => [
                        'toolbar' => [
                            'show' => false,
                        ],
                    ],
                    'tooltip' => [
                        'enabled' => false, // Disable tooltips
                    ],
                    'xaxis' => [
                        'type' => 'category',
                        'categories' => $bellyWaist['categories'],
                    ],
                    'plotOptions' => [
                        'bar' => [
                            'horizontal' => true,
                            'endingShape' => 'rounded'
                        ],
                    ],
                    'dataLabels' => [
                        'enabled' => true
                    ],
                    'stroke' => [
                        'show' => true,
                        'curve' => 'smooth',
                    ],
                ],
                'series' => $bellyWaist['series']
            ]); ?>
        <?php } ?>
    </div>
</div>