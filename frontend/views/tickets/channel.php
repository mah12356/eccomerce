<?php

/** @var $channel common\models\Channels */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Chats;

$this->registerCssFile('@web/css/tickets.css');
$this->title = $channel['name'];
$url = Yii::$app->urlManager;
?>

<div class="chat-section">
    <div class="header">
        <div class="grp-info">
            <h3 class="grp-name"><?= $channel['name'] ?></h3>
        </div>
    </div>
    <?php if ($channel['chats']) { ?>
        <div class="chat-box">
            <?php foreach (array_reverse($channel['chats']) as $chat) { ?>
                <?php if ($chat['sender'] == Chats::SENDER_ADMIN) { ?>
                    <div class="chat-admin">
                        <div class="msg">
                            <p class="m-0"><?= $chat['text'] ?></p>
                            <?php if (Gadget::fileExist($chat['image'], Chats::UPLOAD_PATH)) { ?>
                                <a class="d-block text-decoration-none text-info mt-2" href="<?= $url->createUrl(['/tickets/download-content', 'content' => $chat['image']]) ?>">
                                    <img class="w-100 h-auto" src="<?= Gadget::showFile($chat['image'], Chats::UPLOAD_PATH) ?>" alt="">
                                </a>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['audio'], Chats::UPLOAD_PATH)) { ?>
                                <audio controls>
                                    <source src="<?= Gadget::showFile($chat['audio'], Chats::UPLOAD_PATH) ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['video'], Chats::UPLOAD_PATH)) { ?>
                                <video class="w-100 h-auto" controls>
                                    <source src="<?= Gadget::showFile($chat['video'], Chats::UPLOAD_PATH) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['document'], Chats::UPLOAD_PATH)) { ?>
                                <a class="d-block text-decoration-none text-info mt-2" href="<?= $url->createUrl(['/tickets/download-content', 'content' => $chat['document']]) ?>">دانلود فایل</a>
                            <?php } ?>
                            <p class="date mt-2 m-0"><?= Jdf::jdate('H:i - y/m/d', $chat['date']) ?></p>
                        </div>
                    </div>
                <?php }else { ?>
                    <div class="chat-client">
                        <div class="msg">
                            <p class="m-0"><?= $chat['text'] ?></p>
                            <?php if (Gadget::fileExist($chat['image'], Chats::UPLOAD_PATH)) { ?>
                                <a class="d-block text-decoration-none text-info mt-2" href="<?= $url->createUrl(['/tickets/download-content', 'content' => $chat['image']]) ?>">
                                    <img class="w-100 h-auto" src="<?= Gadget::showFile($chat['image'], Chats::UPLOAD_PATH) ?>" alt="">
                                </a>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['audio'], Chats::UPLOAD_PATH)) { ?>
                                <audio controls>
                                    <source src="<?= Gadget::showFile($chat['audio'], Chats::UPLOAD_PATH) ?>" type="audio/mpeg">
                                    Your browser does not support the audio element.
                                </audio>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['video'], Chats::UPLOAD_PATH)) { ?>
                                <video class="w-100 h-auto" controls>
                                    <source src="<?= Gadget::showFile($chat['video'], Chats::UPLOAD_PATH) ?>" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            <?php } ?>
                            <?php if (Gadget::fileExist($chat['document'], Chats::UPLOAD_PATH)) { ?>
                                <a class="d-block text-decoration-none text-info mt-2" href="<?= $url->createUrl(['/tickets/download-content', 'content' => $chat['document']]) ?>">دانلود فایل</a>
                            <?php } ?>
                            <p class="date mt-2 m-0"><?= Jdf::jdate('H:i - y/m/d', $chat['date']) ?></p>
                        </div>
                    </div>
                <?php } ?>
            <?php } ?>
        </div>
    <?php }else { ?>
        <div class="chat-box">
            <div class="chat">
                <p class="default text-center">در حال حاضر هیچ پیامی در این تیکت ارسال نشده است</p>
            </div>
        </div>
    <?php } ?>
</div>