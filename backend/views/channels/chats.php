<?php

/** @var $model common\models\Chats */
/** @var $channel common\models\Tickets */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Chats;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;

$this->registerCssFile('@web/css/tickets.css');
$url = Yii::$app->urlManager;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ لیست کانال‌ها'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container mt-5">
    <div class="row">
        <div class="col-6">
            <div class="chat-section">
                <div class="chat-box-header">
                    <div class="grp-info">
                        <h3 class="grp-name"><?= $this->title ?></h3>
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
                                        <p class="date d-inline mt-2 m-0"><?= Jdf::jdate('H:i - y/m/d', $chat['date']) ?></p>
                                        <?= Html::a(Yii::t('app', 'حذف'), ['delete-chat', 'id' => $chat['id']], [
                                            'class' => 'delete d-inline mt-2 m-0 ms-3',
                                            'data' => [
                                                'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                            <?php }?>
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
        </div>
        <div class="col-6">
            <div class="tickets-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
                <div class="row">
                    <div class="col-lg-6 col-md-6 col-12"><?= $form->field($model, 'imageFile')->fileInput() ?></div>
                    <div class="col-lg-6 col-md-6 col-12"><?= $form->field($model, 'audioFile')->fileInput() ?></div>
                    <div class="col-lg-6 col-md-6 col-12"><?= $form->field($model, 'videoFile')->fileInput() ?></div>
                    <div class="col-lg-6 col-md-6 col-12"><?= $form->field($model, 'documentFile')->fileInput() ?></div>
                </div>

                <div class="form-group text-center mt-3">
                    <?= Html::submitButton(Yii::t('app', 'ارسال'), ['class' => 'btn btn-success']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
