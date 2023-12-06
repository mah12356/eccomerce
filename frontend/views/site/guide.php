<?php

/** @var $model common\models\Media */

use common\components\Gadget;
use common\models\Media;
use yii\web\View;

$this->registerCssFile('@web/css/tabs.css');
$this->registerJsFile('@web/js/tabs.js', [
    'position' => View::POS_END
]);

$url = Yii::$app->urlManager;
?>

<div class="container">
    <div class="row my-5">
        <?php foreach ($model as $item) { ?>
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <a href="<?= $url->createUrl(['/site/media', 'id' => $item['id']]) ?>" class="text-decoration-none">
                    <div class="card w-100">
                        <?php
                        if ($item['poster'] != null) {
                        ?>
                        <img src="<?= Gadget::showFile($item['poster'], Media::UPLOAD_PATH) ?>" class="card-img-top" alt="...">
                            <?php
                        }
                        ?>
                        <div class="card-body">
                            <h5 class="card-title text-center"><?= $item['title'] ?></h5>
                            <p class="card-text text-center"><?= $item['caption'] ?></p>
                        </div>
                    </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<!--حذف شده-->
<div class="container d-none">
    <div class="row my-5">
        <div class="tab p-0">
            <button id="default" class="tablinks" onclick="previewTab(event, 'register')">ثبت نام</button>
            <button class="tablinks" onclick="previewTab(event, 'live')">شرکت در جلسات</button>
            <button class="tablinks" onclick="previewTab(event, 'regimes')">برنامه غذایی</button>
            <button class="tablinks" onclick="previewTab(event, 'messenger')">پیام‌رسان</button>
        </div>

        <div id="register" class="tabcontent">
            <video class="w-100 h-auto" controls>
                <source src="/upload/statics/register.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div id="live" class="tabcontent">
            <video class="w-100 h-auto" controls>
                <source src="/upload/statics/live.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div id="regimes" class="tabcontent">
            <video class="w-100 h-auto" controls>
                <source src="/upload/statics/regimes.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
        <div id="messenger" class="tabcontent">
            <video class="w-100 h-auto" controls>
                <source src="/upload/statics/messenger.mp4" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        </div>
    </div>
</div>
