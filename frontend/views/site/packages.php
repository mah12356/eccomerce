<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Packages;

/** @var yii\web\View $this */
/** @var Packages $model */

$url = Yii::$app->urlManager;
?>
<section class="packages-section w-100">

    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12">
                <video class="w-100 h-auto rounded-3" controls>
                    <source src="/upload/landing.mp4">
                </video>
            </div>
            <div class="col-lg-6 col-12">
                <p class="mt-5">
                    <b>آیا به دنبال بهبودی در زندگی خود از نظر جسمی و روحی هستید؟</b>
                    <strong>مهساآنلاین</strong> با همراه یک تیم حرفه ای، همواره آماده کمک به شما عزیزان است. ما در اینجا با داشتن دوره های ورزشی شاداب و پویا به همراه حرکات اصلاحی برای ساخت و فرم دهی بدن، دوره ماساژ صورت برای جوان سازی پوست و همینطور با داشتن رژیم های غذایی و رژیم شوک یک پکیج کامل برای کسب نتیجه سریع را برای شما آماده کرده ایم که شما میتوانید در طول این مسیر قدم به قدم با پشتیبان های <strong>مهساآنلاین</strong> همراه شوید و یک زندگی شاداب و سلامت را تجربه کنید.
                </p>
                <p class="mt-4">برای شرکت در کلاس های مهساآنلاین میتوانید در یکی از پکیج ها ثبت نام کنید</p>
                <a href="#package-section" class="btn btn-success"><span>انتخاب پکیج</span><i class="fa fa-arrow-down me-2"></i></a>
            </div>
        </div>
    </div>

    <h1 class="title mt-5">دوره های مهساآنلاین</h1>
    <div class="container">
        <div id="package-section" class="row">
            <?php foreach ($model as $item) { ?>
                <div class="col-md-4 mb-4">
                    <div class="packages-package-card">
                        <div class="header">
                            <img src="<?= Gadget::showFile($item['poster'], Packages::UPLOAD_PATH) ?>" alt="<?= $item['alt'] ?>">
                        </div>
                        <div class="body">
                            <p class="name"><?= $item['name'] ?></p>
<!--                            <p class="course-list m-0">لیست دوره ها</p>-->
                            <p class="courses">
                                <?php
                                $price = 0;
                                foreach ($item['courses'] as $course) {
                                    $price += $course['price'];
                                } ?>
                            </p>

                            <p class="price"><?= Gadget::calculateDiscount($price, $item['discount']) ?> تومان</p>
                            <a class="register" href="<?= $url->createUrl(['/site/package-detail', 'id' => $item['id']]) ?>">ثبت نام</a>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</section>