<?php

/** @var View $this */

/** @var string $content */

use common\components\Gadget;
use common\components\Jdf;
use common\components\Tools;
use common\models\Hints;
use common\models\Packages;
use common\models\Register;
use common\modules\main\models\Category;
use common\modules\main\models\Config;
use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\helpers\ArrayHelper;
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

$packageCategories = Category::find()->where(['belong' => Category::BELONG_COURSE])->asArray()->all();
unset($packageCategories[count($packageCategories) - 1]);

$packages = Packages::find()->where(['>', 'start_register', Jdf::jmktime()])->andWhere(['status' => Packages::STATUS_READY])
    ->asArray()->all();

$hints = null;

if (!Yii::$app->user->isGuest) {
    $package = Packages::find()->where(['status' => Packages::STATUS_READY])->asArray()->all();
    $register = Register::find()->where(['user_id' => Yii::$app->user->id, 'payment' => Register::PAYMENT_ACCEPT])
        ->andWhere(['IN', 'package_id', ArrayHelper::map($package, 'id', 'id')])
        ->asArray()->all();

    if ($register) {
        $hints = Hints::find()->where(['belong' => Hints::BELONG_HOME_PAGE])->groupBy(['title'])
            ->orderBy(['id' => SORT_DESC])->limit(3)->asArray()->all();
    }
}

if ($packages || $hints) {
    $this->registerCssFile('@web/css/countdown.css');
    $this->registerJsFile('@web/js/countdown.js', [
        'position' => View::POS_END,
    ]);
}

$clientOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);

if ($_SESSION['appMode']) {
    if ($clientOs['belong'] == 'mobile') {
        $this->registerCssFile('@web/css/app.css');
        $this->registerJsFile('@web/js/app.js', [
            'position' => View::POS_END
        ]);
    }
}
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


    <?php if ($clientOs['belong'] == 'mobile' && $_SESSION['appMode']) { ?>
        <div class="container-fluid mobile-header">
            <div class="row">
                <div class="col-4">
                    <div class="layout-container">
                        <aside class="overlay-sidebar" aria-label="Sidebar">
                            <nav>
                                <button class="toggle-button" aria-expanded="false" aria-controls="primary-navigation"
                                        title="Toggle Navigation">
                                    <svg class="menu-icon-svg" viewBox="0 0 100 75">
                                        <rect y="0" width="100" height="14" rx="6"></rect>
                                        <rect y="30" width="100" height="14" rx="6"></rect>
                                        <rect y="60" width="100" height="14" rx="6"></rect>
                                    </svg>
                                </button>
                                <ul id="primary-navigation" class="primary-navigation" data-hidden="true">
                                    <li class="nav-item">
                                        <header class="primary-navigation--header">
                                            <span class="header__icon"><i class="fas fa-user-circle"></i></span>
                                            <h2 class="user__info">
                                                <?php if (!Yii::$app->user->isGuest) { ?>
                                                    <span class="user__name"><?= Yii::$app->user->identity->name ?> <?= Yii::$app->user->identity->lastname ?></span>
                                                <?php }else { ?>
                                                    <span class="user__name">مهساآنلاین</span>
                                                <?php } ?>
                                            </h2>
                                        </header>
                                        <ul class="sub__navigation--container">
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/dashboard']) ?>" class="nav__link">
                                                    <i class="fas fa-home"></i>
                                                    <span class="text">داشبورد</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/packages']) ?>" class="nav__link">
                                                    <i class="fas fa-certificate"></i>
                                                    <span class="text">پکیج ها</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/blogs']) ?>" class="nav__link">
                                                    <i class="fas fa-newspaper"></i>
                                                    <span class="text">مقالات</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/contact']) ?>" class="nav__link">
                                                    <i class="fas fa-phone"></i>
                                                    <span class="text">تماس با ما</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/about']) ?>" class="nav__link">
                                                    <i class="fas fa-users"></i>
                                                    <span class="text">درباره ما</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/guide']) ?>" class="nav__link">
                                                    <i class="fas fa-info-circle"></i>
                                                    <span class="text">راهنما</span>
                                                </a>
                                            </li>
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/download-app']) ?>" class="nav__link">
                                                    <i class="fas fa-mobile"></i>
                                                    <span class="text">دانلود اپلیکیشن</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="sub__navigation--container">
                                            <li class="sub__nav-item">
                                                <a href="<?= $url->createUrl(['/site/profile']) ?>" class="nav__link">
                                                    <i class="fas fa-user"></i>
                                                    <span class="text">حساب کاربری</span>
                                                </a>
                                            </li>
                                            <?php if (!Yii::$app->user->isGuest) { ?>
                                                <li class="sub__nav-item">
                                                    <a href="<?= $url->createUrl(['/packages/index']) ?>" class="nav__link">
                                                        <i class="fas fa-shopping-cart "></i>
                                                        <span class="text">دوره‌های من</span>
                                                    </a>
                                                </li>
                                                <li class="sub__nav-item">
                                                    <?= Html::a('<i class="fas fa-right-from-bracket"></i>
                                                    <span class="text">خروج از حساب</span>', ['/site/logout'], ['data-method' => 'post', 'class' => 'nav__link']) ?>
                                                </li>
                                            <?php } ?>
                                        </ul>
                                    </li>
                                </ul>
                            </nav>
                        </aside>
                    </div>
                </div>
                <div class="col-8">
                    <?php if (!Yii::$app->user->isGuest) { ?>
                        <p class="header-user-icon m-0">
                            <b><?= Yii::$app->user->identity->name ?> <?= Yii::$app->user->identity->lastname ?></b>
                            <i class="fa fa-user-circle"></i>
                        </p>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } else { ?>
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
<!--                                <li><a class="nav-link" href="--><?php //= $url->createUrl('/site/club') ?><!--"><span>باشگاه مربیان</span></a></li>-->
                                <li class="dropdown"><a href="<?= $url->createUrl('/site/blogs') ?>"><span>مجله</span>
                                        <i class="bi bi-chevron-down"></i></a>
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
                        <?php if (Yii::$app->user->isGuest) { ?>
                            <a class="register-icon p-0" href="<?= $url->createUrl('/site/login') ?>">
                                <img src="/upload/img/6681204.png" class="w-100 h-100" alt="">
                            </a>
                        <?php } else { ?>
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
    <?php } ?>

    <!-- ======= Hero ======= -->
    <section class="hero-section">
        <div class="container-fluid">
            <div class="layout">
                <h1>مهسا آنلاین</h1>

                <?php if ($packages) { ?>
                <div class="container-fluid">
                    <div class="row pt-4">
                        <?php foreach ($packages

                        as $item) { ?>
                        <?php if (count($packages) == 1) { ?>
                        <div class="col-12">
                            <?php }else { ?>
                            <div class="col-6">
                                <?php } ?>
                                <p class="package-title mx-auto"><?= $item['name'] ?></p>
                                <div class="counter-section">
                                    <input id="<?= $item['id'] ?>" type="hidden"
                                           value="<?= date('F d, Y, 00:00:00', $item['start_register']) ?>">
                                    <ul dir="ltr" class="counter-list p-0">
                                        <li class="counter"><span id="days-<?= $item['id'] ?>" class="days"></span>روز
                                        </li>
                                        <li class="counter"><span id="hours-<?= $item['id'] ?>" class="hours"></span>ساعت
                                        </li>
                                        <li class="counter"><span id="minutes-<?= $item['id'] ?>"
                                                                  class="minutes"></span>دقیقه
                                        </li>
                                        <li class="counter"><span id="seconds-<?= $item['id'] ?>"
                                                                  class="seconds"></span>ثانیه
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } else { ?>
                        <div class="row">
                            <div class="col-6">
                                <img class="slogan" src="/upload/img/HeroTitle.png" alt="">
                            </div>
                            <div class="col-6">
                                <div class="data-section">
                                    <img src="/upload/img/logo.png" alt="">
                                    <?php foreach ($packageCategories as $item) { ?>
                                        <a href="<?= $url->createUrl(['/site/packages', 'category_id' => $item['id']]) ?>"
                                           target="_blank"><?= $item['title'] ?></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php if (!$hints) { ?>
                            <div class="hero-contact">
                                <p><?= $phone ?><i class="fa fa-phone me-2"></i></p>
                                <p><?= $email ?><i class="fa fa-envelope me-2"></i></p>
                            </div>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($hints) { ?>
                        <?php if ($packages) {
                            $class = 'hint-section';
                        } else {
                            $class = 'hero-contact';
                        } ?>
                        <div class="<?= $class ?>">
                            <p class="hint-title">اعلانات</p>
                            <?php foreach ($hints as $item) { ?>
                                <div class="hint-bar">
                                    <p class="hint">
                                        <?php switch ($item['type']) {
                                            case Hints::TYPE_NOTIFICATION:
                                                echo '<span class="d-block text-success">اطلاعیه</span>';
                                                break;
                                            case Hints::TYPE_INSTANT:
                                                echo '<span class="d-block text-danger">فوری</span>';
                                                break;
                                        } ?>
                                        <span><?= $item['title'] ?></span>
                                        <?php if ($item['link']) { ?>
                                            <a class="mx-2" href="<?= $item['link'] ?>"><i class="fa fa-reply"></i></a>
                                        <?php } ?>
                                    </p>
                                    <p class="text"><?= $item['text'] ?></p>
                                    <p class="date"><?= Jdf::jdate('Y/m/d', $item['date']) ?></p>
                                </div>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
    </section>

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid p-0">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
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
                    <a href="<?= $url->createUrl(['/site/download-app']) ?>"><img class="download-app" src="/upload/assets/direct-download.svg" alt="دانلود اپلیکیشن"></a>
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
    <div  style="height: 400px" class="modal fade" id="exampleModalFullscreenSm" tabindex="-1" aria-labelledby="exampleModalFullscreenSmLabel" aria-hidden="true">
        <div class="modal-dialog modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-body align-center">
                    <img  class="img-fluid" src="/upload/img/baner.jpg">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
                    <a href="/site/login"  class="btn btn-secondary" >دریافت جایزه</a>
                </div>
            </div>
        </div>
    </div>
    </body>
    </html>
    <script type="text/javascript">
        $(window).on('load', function() {
            $('#exampleModalFullscreenSm').modal('show');
        });
    </script>
<?php $this->endPage();
