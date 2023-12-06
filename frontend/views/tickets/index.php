<?php

use common\components\Gadget;
use common\models\Channels;
use common\models\Tickets;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\models\Tickets $model */
/** @var common\models\Channels $channels */

$this->title = Yii::t('app', 'پیام‌رسان مهساآنلاین');
$url = Yii::$app->urlManager;
?>

<div class="container">
    <div class="row">
        <p class="tickets-title"><?= $this->title ?></p>
        <hr>
        <?php foreach ($model as $item) { ?>
            <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                <a class="text-decoration-none text-dark" href="<?= $url->createUrl(['/tickets/chats', 'id' => $item['id'],'type'=>$item['type'],'check'=>'check']) ?>">
                    <div class="ticket-box">
                    <div class="row">
                        <div class="item">
                            <?php switch ($item['type']) {
                                case Tickets::TYPE_DIET:
                                    echo '<img class="avatar" src="/upload/statics/diet-ticket.png" alt="">';
                                    break;
                                case Tickets::TYPE_SUPPORT:
                                    echo '<img class="avatar" src="/upload/statics/support-ticket.png" alt="">';
                                    break;
                                case Tickets::TYPE_COACH:
                                    echo '<img class="avatar" src="/upload/statics/coach-ticket.png" alt="">';
                                    break;
                                case Tickets::TYPE_ANALYZE:
                                    echo '<img class="avatar" src="/upload/statics/analyze-ticket.png" alt="">';
                                    break;
                            } ?>
                            <?php if ($item['status'] == Tickets::STATUS_NEW_MESSAGE_CLIENT) { ?>
                                <span class="notify-badge">پیام جدید</span>
                            <?php } ?>
                        </div>
                    </div>
                    <p class="text-center"><b><?= $item['title'] ?></b></p>
                </div>
                </a>
            </div>
        <?php } ?>
    </div>
</div>

<?php if ($channels) { ?>
    <div class="container">
        <div class="row">
            <p class="tickets-title mt-5">کانال‌های مهساآنلاین</p>
            <hr>
            <?php foreach ($channels as $item) { ?>
                <div class="col-lg-3 col-md-6 col-sm-6 col-12 my-3">
                    <a class="text-decoration-none text-dark" href="<?= $url->createUrl(['/tickets/channel', 'id' => $item['id']]) ?>">
                        <div class="ticket-box">
                            <div class="row">
                                <div class="item">
                                    <img class="avatar" src="<?= Gadget::showFile($item['avatar'], Channels::UPLOAD_PATH) ?>" alt="<?= $item['name'] ?>">
                                </div>
                            </div>
                            <p class="text-center"><b><?= $item['name'] ?></b></p>
                        </div>
                    </a>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>