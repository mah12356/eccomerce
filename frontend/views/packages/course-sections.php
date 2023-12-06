<?php

/** @var $model common\models\Sections */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Sections;

$url = Yii::$app->urlManager;
?>

<div class="container">
    <div class="row">
        <?php foreach ($model as $item) { ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                <div class="section-box">
                    <p class="text-center"><b>عنوان جلسه : </b><span><?= Gadget::convertToPersian($item['title']) ?></span></p>
                    <p class="text-center">
                        <b>وضعیت جلسه : </b>
                        <?php switch ($item['mood']) {
                            case Sections::MOOD_PENDING:
                                echo '<span class="text-dark">در انتظار پخش</span>';
                                break;
                            case Sections::MOOD_READY:
                                echo '<span class="text-info">آماده پخش</span>';
                                break;
                            case Sections::MOOD_PLAYING:
                                echo '<span class="text-primary">در حال پخش</span>';
                                break;
                            case Sections::MOOD_COMPLETE:
                                echo '<span class="text-success">ذخیره موفق</span>';
                                break;
                            case Sections::MOOD_FAILED:
                                echo '<span class="text-danger">ذخیره نا موفق</span>';
                                break;
                        } ?>
                    </p>
                    <p class="text-center"><b>تاریخ برگزاری : </b><span><?= Jdf::jdate('Y/m/d', $item['date']) ?></span></p>
                    <div class="row">
                        <div class="col-6"><p class="text-center"><b>ساعت شروع</b></p></div>
                        <div class="col-6"><p class="text-center"><b>ساعت پایان</b></p></div>
                    </div>
                    <div class="row">
                        <div class="col-6"><p class="text-center"><?= Jdf::jdate('H:i', $item['start_at']) ?></p></div>
                        <div class="col-6"><p class="text-center"><?= Jdf::jdate('H:i', $item['end_at']) ?></p></div>
                    </div>
                    <?php switch ($item['mood']) {
                        case Sections::MOOD_PLAYING:
                            echo '<a class="section-box-btn" target="_blank" href="'. $url->createUrl(['/packages/live-stream', 'id' => $item['id']]) .'">شرکت در جلسه</a>';
                            break;
                        case Sections::MOOD_COMPLETE:
                            echo '<a class="section-box-btn" href="'. $url->createUrl(['/packages/section-content', 'id' => $item['id']]) .'">مشاهده</a>';
                            break;
                        default:
                            echo '<button class="section-box-btn section-box-btn-disable">شرکت در جلسه</button>';
                            break;
                    } ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
