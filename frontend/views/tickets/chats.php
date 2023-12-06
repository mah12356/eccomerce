<?php

/** @var $model common\models\Chats */
/** @var $ticket common\models\Tickets */
/** @var $chatBox frontend\controllers\TicketsController */
/** @var $hint frontend\controllers\TicketsController */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Chats;
use common\models\Tickets;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/css/tickets.css');
$this->title = $ticket['title'];
$url = Yii::$app->urlManager;
?>

<svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
    </symbol>
    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
    </symbol>
    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
    </symbol>
</svg>

<?php if ($hint) { ?>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-12 d-block mx-auto">
                <div class="alert alert-primary d-flex align-items-center" role="alert">
                    <svg class="bi flex-shrink-0 ms-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                        <?= $hint['title'] ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<div class="chat-section">
    <div class="header">
        <div class="row w-100">
            <div class="col-8">
                <div class="grp-info">
                    <h3 class="grp-name"><?= $ticket['title'] ?></h3>
                </div>
            </div>
            <div class="col-4">
                <a href="#form" class="btn btn-light" style="float: left">ارسال پیام</a>
            </div>
        </div>
    </div>
    <?php if ($ticket['chats']) { ?>
    <div class="chat-box">
        <?php foreach (array_reverse($ticket['chats']) as $chat) { ?>
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
                            <audio style="width: 260px" controls>
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
                            <audio style="width: 260px" controls>
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
<hr class="my-5">
<div id="form" class="container">
    <?php if (!$chatBox) { ?>
        <h5 class="text-center">در حال حاضر شما امکان ارسال پیام به مشاور را ندارید</h5>
    <?php }else { ?>
        <h1>ارسال پیام</h1>
        <div class="tickets-form">
            <?php $form = ActiveForm::begin(); ?>
            
            <div class="form-group text-center mt-3">
                <?= Html::submitButton(Yii::t('app', 'ارسال'), ['class' => 'btn btn-success']) ?>
            </div>

            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'imageFile')->fileInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'audioFile')->fileInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'videoFile')->fileInput() ?></div>
                <div class="col-lg-3 col-md-6 col-12"><?= $form->field($model, 'documentFile')->fileInput() ?></div>
            </div>

            <div class="form-group text-center mt-3">
                <?= Html::submitButton(Yii::t('app', 'ارسال'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    <?php } ?>
</div>
