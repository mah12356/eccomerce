<?php

/** @var $live common\models\Sections */
/** @var $placed common\models\Sections */

use common\models\Sections;
use common\components\Gadget;
use common\components\Jdf;

$url = Yii::$app->urlManager;
?>

<div class="d-flex flex-row justify-content-between align-items-between w-100 app-sections" style="visibility: visible; animation-name: fadeInDown;">
    <div class="d-flex justify-content-center pt-2 text-bold w-100 select" id="live">
        <button onclick="changeSections('live')">پخش زنده</button>
    </div>
    <div class="d-flex justify-content-center pt-2 text-bold w-100" id="passed">
        <button onclick="changeSections('passed')">کلاس‌های برگزار شده</button>
    </div>
</div>

<div id="live-tab" style="display: block;" class="container">
    <?php if ($live) { ?>
        <div class="row">
            <?php foreach ($live as $item) { ?>
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
    <?php }else { ?>
        <div class="row">
            <?php
            if ($new == null) {
                ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                    <div class="section-box">
                        <p class="text-center"><b>عنوان جلسه
                                : </b><span>هدیه مهسا آنلاین</span></p>
                        <p class="text-center">
                            <b>وضعیت جلسه : </b>
                            <span class="text-info">آماده پخش</span>
                        </p>
                        <p class="text-center"><b>تاریخ برگزاری
                                : </b><span>1402</span></p>
                        <div class="row">
                            <div class="col-6"><p class="text-center"><b>ساعت شروع</b></p></div>
                            <div class="col-6"><p class="text-center"><b>ساعت پایان</b></p></div>
                        </div>
                        <div class="row">
                            <div class="col-6"><p class="text-center">10:00:00</p>
                            </div>
                            <div class="col-6"><p class="text-center">10:18:00</p>
                            </div>
                        </div>

                        <a class="section-box-btn" href="<?=$url->createUrl(['/packages/hedieh'])?>">مشاهده</a>;

                    </div>
                </div>
                <?php
            }
            ?>
            <p class="text-center mt-5"><b>شما در حال حاضر هیچ کلاسی ندارید</b></p>
        </div>
    <?php } ?>
</div>

<div id="passed-tab" style="display: none;" class="container">
    <?php if ($placed) { ?>
        <div class="row">
            <div class="mt-4 text-center"><b>کلاس های لایو</b></div>
            <?php foreach ($placed as $item) { ?>
                <?php if ($item['type'] == Sections::TYPE_LIVE) { ?>
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
            <?php } ?>
        </div>
        <hr>
        <div class="row">
            <div class="mt-4 text-center"><b>کلاس های آفلاین</b></div>
            <?php foreach ($placed as $item) { ?>
                <?php if ($item['type'] == Sections::TYPE_OFFLINE) { ?>
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
            <?php } ?>
        </div>
    <?php }else { ?>
        <div class="row">
            <p class="text-center mt-5"><b>هیچ کدام از کلاس های شما برگزار نشده است</b></p>
        </div>
    <?php } ?>
</div>