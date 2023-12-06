<?php

/** @var $notification frontend\controllers\SiteController */
/** @var $packages frontend\controllers\SiteController */
/** @var $blogs frontend\controllers\SiteController */
/** @var $hints frontend\controllers\SiteController */

use common\components\Gadget;
use common\models\Hints;
use common\models\Packages;
use common\components\Jdf;
use common\modules\blog\models\Articles;

$url = Yii::$app->urlManager;

$clientOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);
?>

<?php if ($clientOs['belong'] == 'mobile') { ?>

    <?php if ($hints) { ?>
        <div class="container mt-5">
            <p><b>اعلانات</b></p>
            <div class="row">
                <?php foreach ($hints as $item) { ?>
                    <div class="hints">
                        <?php switch ($item['type']) {
                            case Hints::TYPE_NOTIFICATION:
                                echo '<span class="d-block text-success"><b>اطلاعیه</b></span>';
                                break;
                            case Hints::TYPE_INSTANT:
                                echo '<span class="d-block text-danger"><b>فوری</b></span>';
                                break;
                        } ?>
                        <i class="fa fa-bell"></i><span><?= $item['title'] ?></span>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>

    <div class="container mt-3">
        <div class="row">
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/packages/all-sections']) ?>">
                        <img src="/upload/statics/app_camera.png" alt="">
                        <b>پخش زنده</b>
                    </a>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/analyze/index']) ?>">
                        <img src="/upload/statics/data_analysis.webp" alt="">
                        <b>آنالیز بدنی</b>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/diet/index']) ?>">
                        <img src="/upload/statics/app_diet.png" alt="">
                        <b>برنامه غذایی</b>
                    </a>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/tickets/index']) ?>">
                        <img src="/upload/statics/app_messenger.png" alt="">
                        <b>پیامرسان</b>
                    </a>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/factor/index']) ?>">
                        <img src="/upload/statics/app_factor.png" alt="">
                        <b>لیست فاکتورها</b>
                    </a>
                </div>
            </div>
            <div class="col-6 mb-3">
                <div class="app-links">
                    <a href="<?= $url->createUrl(['/site/guide']) ?>">
                        <img src="/upload/statics/app_guide.png" alt="">
                        <b>مطالب آمورزشی</b>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <?php if ($packages) { ?>
        <div class="container mt-5">
            <p class="py-2 text-center"><b>پکیج های مهساآنلاین</b></p>
            <?php foreach ($packages as $item) { ?>
                <div class="row">
                    <div class="app-package">
                        <p class="pn"><?= $item['name'] ?></p>
                        <p><span>شروع ثبت نام : </span><span><?= Jdf::jdate('Y/m/d', $item['start_register']) ?></span></p>
                        <p><span>پایان ثبت نام : </span><span><?= Jdf::jdate('Y/m/d', $item['end_register']) ?></span></p>
                        <a class="btn btn-light text-center mx-auto d-block mt-2"
                           href="<?= $url->createUrl(['/site/package-detail', 'id' => $item['id']]) ?>"><b>ثبت نام</b></a>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php } ?>

    <div class="container bg-white mt-3">
        <p class="pt-5 text-center"><b>هر آن چیزی که نیاز دارید</b></p>
        <p class="text-center app-guide-title">برای مشاهده ویدیو‌ آموزشی روی بخش مورد نظر کلیک کنید</p>
<!--        <p class="pb-3 text-center">-->
<!--            <a class="app-guide">خرید پکیج</a>-->
<!--            <a class="app-guide">شرکت در کلاس</a>-->
<!--            <a class="app-guide">دریافت رژیم</a>-->
<!--            <a class="app-guide">پیامرسان</a>-->
<!--        </p>-->
        <div class="row">
            <div class="col-6 mb-3">
                <a class="text-decoration-none" href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'live']) ?>">
                    <img class="slogan-poster" src="/upload/statics/s-live.png" alt="">
                </a>
                <a class="slogan-title text-center text-decoration-none text-dark d-block mx-auto"
                   href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'live']) ?>">کلاس های لایو</a>
                <p class="slogan-text">ورزش را در هر مکان تجربه کنید!<br>با کلاس‌های ورزشی آنلاین، بهترین فرصت برای تقویت بدن و روحیه خود را داشته باشید</p>
            </div>
            <div class="col-6 mb-3">
                <a class="text-decoration-none" href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'package']) ?>">
                    <img class="slogan-poster" src="/upload/statics/s-offline.png" alt="">
                </a>
                <a class="slogan-title text-center text-decoration-none text-dark d-block mx-auto"
                   href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'package']) ?>">خرید پکیچ</a>
                <p class="slogan-text">آموزش‌ها در دسترس شما!<br>با ویدیوهای ذخیره شده، می‌توانید بهترین آموزش‌ها را هر زمانی که می‌خواهید تماشا کنید</p>
            </div>
            <div class="col-6 mb-3">
                <a class="text-decoration-none" href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'messenger']) ?>">
                    <img class="slogan-poster" src="/upload/statics/s-support.png" alt="">
                </a>
                <a class="slogan-title text-center text-decoration-none text-dark d-block mx-auto"
                   href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'messenger']) ?>">پیام رسان</a>
                <p class="slogan-text">همیشه در دسترس!<br>با پشتیبانی ۲۴ ساعته، همیشه پاسخگوی نیازهای شما هستیم</p>
            </div>
            <div class="col-6 mb-3">
                <a class="text-decoration-none" href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'diet']) ?>">
                    <img class="slogan-poster" src="/upload/statics/s-diet.png" alt="">
                </a>
                <a class="slogan-title text-center text-decoration-none text-dark d-block mx-auto"
                   href="<?= $url->createUrl(['/site/usage-videos', 'section' => 'diet']) ?>">برنامه غذایی</a>
                <p class="slogan-text">رژیم غذایی با راهنمایی مشاوران تغذیه، راهی مطمئن به سلامتی و تعادل بین بدن و ذهن شما</p>
            </div>
        </div>
    </div>

    <?php if ($blogs) { ?>
        <div class="container bg-white mt-5">
            <p class="py-5 text-center"><b>جدید ترین مقالات</b></p>
            <div class="row">
                <?php foreach ($blogs as $item) { ?>
                    <div class="col-12">
                        <div class="blog-card">
                            <div class="blog-card-banner">
                                <img src="<?= Gadget::showFile($item['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                            </div>
                            <div class="blog-card-content">
                                <h3><?= $item['title'] ?></h3>
                                <div><?= $item['introduction'] ?></div>
                                <a href="<?= $url->createUrl(['/site/article-view', 'id' => $item['id']]) ?>">ادامه مطلب</a>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
<?php }else { ?>
    <div class="container">
        <div class="row">
            <p><b>اطلاعیه</b></p>
            <ul class="list-group">
                <?php foreach ($notification as $item) {
                    switch ($item['type']) {
                        case 'section':
                            echo '<li class="list-group-item"><a href="'. $url->createUrl(['/package/live-stream', 'id' => $item['id']]) .'">شرکت در جلسه : '. $item['title'] .'</a></li>';
                            break;
                        case 'diet':
                            echo '<li class="list-group-item"><a href="'. $url->createUrl(['/diet/get-regime', 'id' => $item['id']]) .'">پاسخ به سوالات برای دریافت برنامه غذایی</a></li>';
                            break;
                        case 'factor':
                            echo '<li class="list-group-item"><a href="'. $url->createUrl(['/factor/view', 'id' => $item['id']]) .'">پرداخت فاکتور با '. $item['title'] .'</a></li>';
                            break;
                    }
                } ?>
            </ul>
        </div>
    </div>
<?php } ?>
