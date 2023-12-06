<?php

use common\components\Gadget;
use common\models\Channels;
use common\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Tickets $idDiet */
/** @var common\models\Tickets $idSupport */
/** @var common\models\Channels $channels */

$this->title = Yii::t('app', 'پیام‌رسان مهساآنلاین');
$url = Yii::$app->urlManager;
?>

    <div class="container">
        <div class="row">
            <p class="tickets-title">نوع سوال</p>
            <hr>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                    <a class="text-decoration-none text-dark" href="<?= $url->createUrl(['/tickets/chats', 'id' => $idSupport->id,'type'=>$idSupport->type]) ?>">
                        <div class="ticket-box">
                            <div class="row">
                                <span>اگر سوالتون غیر تخصصی است یعنی تغذیه فعال نشده ، یا فایل آن مشکل دارد اینگونه موضوعات از اینجا سوالتون بپرسید</span>
                                <div class="item">
                                    <img class="avatar" src="/upload/statics/supportNew.png" alt="">
                                     <span class="notify-badge">پیام پشتیبانی</span>
                                </div>
                            </div>
                            <p class="text-center"><b>پیام جدید </b></p>
                        </div>
                    </a>
                </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                <a class="text-decoration-none text-dark" href="<?= $url->createUrl(['/tickets/chats', 'id' => $idDiet->id,'type'=>$idDiet->type]) ?>">
                    <div class="ticket-box">
                        <span>پیام تخصصی مثل میزان کالری یک غذا ، موضوعات مربوط به منو غذایی و هر گونه سوال تخصصی دیگر</span>
                        <div class="row">
                            <div class="item">
                                <img class="avatar" src="/upload/statics/dietNew.jpg" alt="">
                                <span class="notify-badge">پیام تغذیه</span>

                            </div>
                        </div>
                        <p class="text-center"><b>پیام جدید </b></p>
                    </div>
                </a>
            </div>

        </div>
    </div>
