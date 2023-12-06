<?php
/** @var yii\web\View $this */
/* @var $email common\modules\main\models\Config */
/* @var $address common\modules\main\models\Config */
/* @var $phone common\modules\main\models\Config */
/* @var $mobile common\modules\main\models\Config */

$url = Yii::$app->urlManager;
?>

<div class="container">
    <div class="row">
        <h1 class="info-head">تماس با ما</h1>
    </div>
    <div class="row info-box">
        <div class="col-md-6 pt-5 order-md-1 order-2">
            <div class="info"><img src="/upload/assets/732200.svg" alt="gmail icon"><span class="content"><?= $email ?></span></div>
            <div class="info"><img src="/upload/assets/1432883.svg" alt="address icon"><span class="content"><?= $address ?></span></div>
            <div class="info"><img src="/upload/assets/3287799.svg" alt="mobile icon"><span class="content"><?= $phone ?></span></div>
        </div>
        <div class="col-md-6 order-md-2 order-1">
            <img class="w-100 h-auto" src="/upload/assets/contact.svg" alt="contact us">
        </div>
    </div>
</div>