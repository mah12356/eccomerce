<?php

/** @var View $this */
/** @var string $content */

use common\widgets\Alert;
use frontend\assets\AppAsset;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\web\View;

AppAsset::register($this);
$url = Yii::$app->urlManager;

$this->registerCssFile('@web/css/panel.css');
$this->registerCssFile('@web/css/app.css');
$this->registerJsFile('@web/js/app.js', [
    'position' => View::POS_END
]);
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

    <main role="main" class="flex-shrink-0">
        <div class="container-fluid p-0">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <div class="back-button-box mt-5 pt-3">
                <a href="<?= $url->createUrl(['/site/navigate']) ?>"><i class="bi bi-arrow-right-circle-fill"></i><span
                        class="mx-2">بازگشت</span></a>
            </div>
            <?= $content ?>
        </div>
    </main>
    
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage();
