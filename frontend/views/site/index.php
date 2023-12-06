<?php

/** @var yii\web\View $this */
/** @var $post frontend\controllers\SiteController */
/** @var $process frontend\controllers\SiteController */
/** @var $packages frontend\controllers\SiteController */
/** @var $offers frontend\controllers\SiteController */
/** @var $comments frontend\controllers\SiteController */
/** @var $selectedArticle frontend\controllers\SiteController */
/** @var $newArticle frontend\controllers\SiteController */
/** @var $faq frontend\controllers\SiteController */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Comments;
use common\models\Packages;
use common\modules\blog\models\Articles;
use common\modules\blog\models\Gallery;
use common\modules\blog\models\Posts;

$url = Yii::$app->urlManager;
//echo '<pre>';
//print_r($offers);
//exit();
?>
<!-- offer section -->
<?php if ($offers) { ?>
    <section class="w-100 offer-section">
        <h2 class="title">دوره های تخفیفی</h2>
        <div class="container">
            <div class="row">
                <div class="swiper slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($offers as $item) { ?>
                            <div class="swiper-slide">
                                <a class="text-decoration-none mx-auto" href="<?= $url->createUrl(['/site/package-detail', 'id' => $item['id']]) ?>">
                                    <div class="offer-package-card">
                                        <div class="header">
                                            <p><?= $item['name'] ?></p>
                                            <img src="<?= Gadget::showFile($item['poster'], Packages::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                        </div>
                                        <div class="content">
<!--                                            <p class="category m-0">--><?php //= $item['cat']['title'] ?><!--</p>-->
                                            <p class="description my-3 mx-0">
                                                <?php
                                                $price = 0;
                                                foreach ($item['courses'] as $course) {
                                                    $price += $course['price'];
                                                } ?>

                                                <?= $item['motive'] ?>
                                            </p>
                                            <b class="d-block text-center mb-2">مدت زمان‌: <?= $item['period'] ?> روز</b>
                                            <p class="discount"><del><?= number_format($price) ?> تومان</del></p>
                                            <p class="text-center"><b><?= number_format(Gadget::calculateDiscount($price, $item['discount'])) ?> تومان</b></p>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next offers-button-next"></div>
                    <div class="swiper-button-prev offers-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!-- package section -->
<?php if ($packages) { ?>
    <section class="packages-section w-100">
        <h2 class="title">دوره های مهساآنلاین</h2>
        <div class="container">
            <div class="row">
                <div class="swiper slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($packages as $item) { ?>
                            <div class="swiper-slide">
                                <div class="packages-package-card">
                                    <div class="header">
                                        <img src="<?= Gadget::showFile($item['poster'], Packages::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                    </div>
                                    <div class="body">
                                        <p class="name"><?= $item['name'] ?></p>
<!--                                        <p class="category">--><?php //= $item['cat']['title'] ?><!--</p>-->
<!--                                        <p class="course-list m-0">لیست دوره ها</p>-->
                                        <p class="courses">
                                            <?php
                                            $price = 0;
                                            foreach ($item['courses'] as $course) {
                                                $price += $course['price'];
                                            } ?>
                                            <?= $item['motive'] ?>
                                        </p>
<!--                                        <p class="calendar m-0">زمان ثبت نام</p>-->
<!--                                        <p class="calendar m-0">از تاریخ --><?php //= Jdf::jdate('Y/m/d', $item['start_register']) ?><!--</p>-->
<!--                                        <p class="calendar m-0">الی --><?php //= Jdf::jdate('Y/m/d', $item['end_register']) ?><!--</p>-->
                                        <p class="price"><?= $price ?> تومان</p>
                                        <a class="register" href="<?= $url->createUrl(['/site/package-detail', 'id' => $item['id']]) ?>">ثبت نام</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next packages-button-next"></div>
                    <div class="swiper-button-prev packages-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!-- faq section -->
<?php if ($faq) { ?>
    <div class="container">
        <div class="row">
            <h2 class="faq-title">سوالات متداول</h2>
            <section class="faq-container">
                <?php foreach ($faq as $item) { ?>
                    <button class="faq-btn"><p><?= $item['question'] ?></p></button>
                    <div class="faq-content">
                        <p><?= $item['answer'] ?></p>
                    </div>
                <?php } ?>
            </section>
        </div>
    </div>
<?php } ?>
<!-- post section -->
<?php if ($post) { ?>
    <div class="container-fluid mt-5 px-5">
        <?php
        $i = 0;
        foreach ($post as $item) {
            if ($i == 0) {
                $i = 1;
        ?>
                <div class="row my-4">
                    <div class="col-md-2 col-sm-12 col-12">
                        <img class="post-banner" src="<?= Gadget::showFile($item['banner'], 'img') ?>" alt="<?= $item['alt'] ?>">
                    </div>
                    <div class="col-md-10 col-12">
                        <div class="post-content">
                            <h3 class="title"><?= $item['title'] ?></h3>
                            <p class="content"><?= $item['text'] ?></p>
                        </div>
                    </div>
                </div>
        <?php
            }else {
                $i = 0;
        ?>
                <div class="row my-4">
                    <div class="col-md-10 col-sm-12 col-12 order-md-1 order-2">
                        <div class="post-content">
                            <h3 class="title"><?= $item['title'] ?></h3>
                            <p class="content"><?= $item['text'] ?></p>
                        </div>
                    </div>
                    <div class="col-md-2 col-12 order-md-2 order-1">
                        <img class="post-banner" src="<?= Gadget::showFile($item['banner'], Posts::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                    </div>
                </div>
        <?php
            }
        }
        ?>
    </div>
<?php } ?>
<!-- user experience -->
<?php if ($comments) { ?>
    <section class="ux-section w-100">
        <h2 class="title">نظرات همراهان مهسا آنلاین</h2>
        <div class="container">
            <div class="row">
                <div class="swiper slider">
                    <div class="swiper-wrapper">
                        <?php foreach ($comments as $item) { ?>
                            <div class="swiper-slide">
                                <div class="ux-section-card">
                                    <p class="name"><?= $item['name'] ?></p>
                                    <div class="header">
                                        <img src="<?= Gadget::showFile($item['avatar'], Comments::UPLOAD_PATH) ?>" alt="<?= $item['name'] ?>">
                                        <p><?= $item['period'] ?></p>
                                    </div>
                                    <p class="comment"><?= $item['text'] ?></p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-next packages-button-next"></div>
                    <div class="swiper-button-prev packages-button-prev"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!-- user result -->
<?php if ($process) { ?>
    <section class="results-section w-100">
        <h2 class="title">آن چیزی که در انتظار شماست</h2>
        <div class="container">
            <div class="row">
                <div class="swiper carousel">
                    <div class="swiper-wrapper pb-5">
                        <?php foreach ($process as $item) { ?>
                            <div class="swiper-slide pb-5">
                                <div class="results-section-card">
                                    <img src="<?= Gadget::showFile($item['image'], 'img') ?>" alt="<?= $item['alt'] ?>">
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination result-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>
<!-- blogs -->
<?php if ($selectedArticle) { ?>
    <section class="blog-section w-100">
        <h2 class="title">مطالب پیشنهادی مهساآنلاین</h2>
        <div class="container">
            <div class="row">
                <div class="swiper carousel">
                    <div class="swiper-wrapper pb-5">
                       <?php foreach ($selectedArticle as $item) { ?>
                           <div class="swiper-slide pb-5">
                               <div class="blog-section-card">
                                   <p class="date"><?= Jdf::jdate('H:i - Y/m/d', $item['modify_date']) ?></p>
                                   <div class="header">
                                       <?php if (Gadget::fileExist($item['poster'], Articles::UPLOAD_PATH)) { ?>
                                           <img src="<?= Gadget::showFile($item['poster'], Articles::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                        <?php }else { ?>
                                           <img src="<?= Gadget::showFile($item['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                        <?php } ?>
                                   </div>
                                   <div class="content">
                                       <p class="title"><?= $item['title'] ?></p>
                                       <div class="description"><?= $item['introduction'] ?></div>
                                       <a href="<?= $url->createUrl(['/site/article-view', 'id' => $item['id']]) ?>">ادامه مطلب</a>
                                   </div>
                               </div>
                           </div>
                       <?php } ?>
                    </div>
                    <div class="swiper-pagination blog-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>

<?php if ($newArticle) { ?>
    <section class="blog-section w-100">
        <h2 class="title">جدیدترین مقالات مهساآنلاین</h2>
        <div class="container">
            <div class="row">
                <div class="swiper carousel">
                    <div class="swiper-wrapper pb-5">
                        <?php foreach ($newArticle as $item) { ?>
                            <div class="swiper-slide pb-5">
                                <div class="blog-section-card">
                                    <p class="date"><?= Jdf::jdate('H:i - Y/m/d', $item['modify_date']) ?></p>
                                    <div class="header">
                                        <?php if (Gadget::fileExist($item['poster'], Articles::UPLOAD_PATH)) { ?>
                                            <img src="<?= Gadget::showFile($item['poster'], Articles::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                        <?php }else { ?>
                                            <img src="<?= Gadget::showFile($item['banner'], Articles::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                                        <?php } ?>
                                    </div>
                                    <div class="content">
                                        <p class="title"><?= $item['title'] ?></p>
                                        <div class="description"><?= $item['introduction'] ?></div>
                                        <a href="<?= $url->createUrl(['/site/article-view', 'id' => $item['id']]) ?>">ادامه مطلب</a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-pagination blog-swiper-pagination"></div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>