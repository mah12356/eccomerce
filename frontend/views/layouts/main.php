<?php

/** @var View $this */

/** @var string $content */

use common\components\Gadget;
use common\components\Tools;
use common\modules\main\models\Config;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\web\View;

AppAsset::register($this);
$url = Yii::$app->urlManager;

$logo = Config::getKeyContent(Config::KEY_LOGO);
$siteName = Config::getKeyContent(Config::KEY_SITE_NAME);

$slogan = Config::getKeyContent(Config::KEY_SLOGAN);

$instagram = Config::getKeyContent(Config::KEY_INSTAGRAM);
$facebook = Config::getKeyContent(Config::KEY_FACEBOOK);
$telegram = Config::getKeyContent(Config::KEY_TELEGRAM);
$twitter = Config::getKeyContent(Config::KEY_TWITTER);
$whatsapp = Config::getKeyContent(Config::KEY_WHATSAPP);
$linkedin = Config::getKeyContent(Config::KEY_LINKEDIN);


$email = Config::getKeyContent(Config::KEY_EMAIL);
$phone = Config::getKeyContent(Config::KEY_PHONE);
$mobile = Config::getKeyContent(Config::KEY_MOBILE);
$address = Config::getKeyContent(Config::KEY_ADDRESS);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>" class="h-100">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css"/>
        <?php $this->registerCsrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class="d-flex flex-column h-100">
    <?php $this->beginBody() ?>

    <!-- ======= Header ======= -->
    <header id="header" class="d-flex align-items-center">
        <div class="container-fluid d-flex align-items-center justify-content-between">
            <div class="row w-100">
                <div class="col-md-2 col-6">
                    <a href="<?= $url->createUrl('/') ?>" class="logo"><img
                            src="/upload/img/logo.png" alt="" class="img-fluid"></a>
                </div>
                <div class="col-md-9 col-4">
                    <nav id="navbar" class="navbar">
                        <ul class="mx-auto">
                            <li><a class="nav-link" href="<?= $url->createUrl('/') ?>"><span>صفحه اصلی</span></a>
                            </li>
                            <li><a class="nav-link" href="<?= $url->createUrl('/site/packages') ?>"><span>لیست پکیج‌‌ها</span></a>
                            </li>
<!--                            <li><a class="nav-link" href="--><?php //= $url->createUrl('/site/club') ?><!--"><span>باشگاه مربیان</span></a></li>-->
                            <li class="dropdown text-center">
                                <a class="text-center" href="<?= $url->createUrl('/site/blogs') ?>">مجله</a>
                                <i class="bi bi-chevron-down "></i>
                                <ul>
                                    <?php Tools::generateBlogsNav() ?>
                                </ul>

                            </li>
                            <li><a class="nav-link"
                                   href="<?= $url->createUrl('/site/contact') ?>"><span>تماس با ما</span></a></li>
                            <li><a class="nav-link"
                                   href="<?= $url->createUrl('/site/about') ?>"><span>درباره ما</span></a></li>
                        </ul>
                        <i class="bi bi-list mobile-nav-toggle mt-2"></i>
                    </nav><!-- .navbar -->
                </div>
                <div class="col-md-1 col-2">
                    <?php if (Yii::$app->user->isGuest) {?>
                        <a class="register-icon p-0" href="<?= $url->createUrl('/site/login') ?>">
                            <img src="/upload/img/6681204.png" class="w-100 h-100" alt="">
                        </a>
                    <?php } else {?>
                        <div class="dropdown">
                            <button class="btn dropdown-toggle register-icon p-0" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                <img src="/upload/img/6681204.png" class="w-100 h-100" alt="">
                            </button>

                            <ul class="drop-section dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li>
                                    <a class="dropdown-item" href="<?= $url->createUrl('/site/dashboard') ?>">
                                        <i class="fa fa-id-card ms-2"></i><span>ورود به حساب</span>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= $url->createUrl('/site/logout') ?>">
                                        <i class="fa fa-sign-out ms-2"></i><span>خروج</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </header>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid p-0">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <div class="back-button-box mt-5 pt-3">
                <a href="<?= $url->createUrl(['/site/navigate']) ?>">
                    <i class="bi bi-arrow-right-circle-fill"></i>
                    <span class="mx-2">بازگشت</span>
                </a>
            </div>
            <?= $content ?>
        </div>
    </main>

    <footer class="footer mt-auto py-5">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-12">
                    <img class="w-25 d-block mx-auto" src="<?= Gadget::showFile($logo, Config::UPLOAD_FILE) ?>"
                         alt="مهساآنلاین">
                    <p class="my-3 text-center"><?= $slogan ?></p>
                </div>
                <div class="col-md-3 col-12">
                    <p class="footer-titles">دسترسی ها</p>
                    <div class="footer-links">
                        <ul>
                            <li><a href="<?= $url->createUrl(['/site/blogs']) ?>">مجله</a></li>
                            <li><a href="<?= $url->createUrl(['/site/about']) ?>">درباره ما</a></li>
                            <li><a href="<?= $url->createUrl(['/site/contact']) ?>">تماس با ما</a></li>
                            <li><a href="<?= $url->createUrl(['/site/packages']) ?>">پکیج های مهساآنلاین</a></li>
                            <li><a href="<?= $url->createUrl(['/site/guide']) ?>">ویدیو های آموزشی</a></li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-3 col-12">
                    <p class="footer-titles">ارتباط با ما</p>
                    <?php if ($phone) { ?>
                        <p><span>شماره تلفن : </span><a class="text-decoration-none text-white"
                                                        href="tel:<?= $phone ?>"><?= $phone ?></a></p>
                    <?php } ?>
                    <?php if ($email) { ?>
                        <p><span>آدرس ایمیل : </span><a class="text-decoration-none text-white"
                                                        href="mailto:<?= $email ?>"><?= $email ?></a></p>
                    <?php } ?>
                    <?php if ($address) { ?>
                        <p><span>آدرس : </span><span><?= $address ?></span></p>
                    <?php } ?>
                </div>
                <div class="col-md-2 col-12">
                    <img class="d-block w-100 h-auto mt-3" src="/upload/img/enamad.png" alt="نماد اعتماد">
                    <img class="download-app" src="/upload/img/bazar.svg" alt="دانلود اپلیکیشن از بازار">
                    <img class="download-app" src="/upload/img/mayket.svg" alt="دانلود اپلیکیشن از مایکت">
                    <a href="<?= $url->createUrl(['/site/download-app']) ?>"><img class="download-app" src="/upload/img/direct-download.svg" alt="دانلود اپلیکیشن"></a>
                </div>
            </div>
        </div>
    </footer>

    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-KT2XYJELG5"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-KT2XYJELG5');
    </script>

    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
