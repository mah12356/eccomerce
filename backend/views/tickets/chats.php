<?php

/** @var $model common\models\Chats */
/** @var $ticket common\models\Tickets */
/** @var $reference common\models\Chats */
/** @var $answers common\models\Answers */
/** @var $actives common\models\Course */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Chats;
use common\models\Tickets;
use common\models\Questions;
use dosamigos\tinymce\TinyMce;
use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\web\View;

$this->registerCssFile('@web/css/tickets.css');
$url = Yii::$app->urlManager;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ تیکت ها'), 'url' => ['index', 'type' => $ticket['type'], 'status' => $ticket['status']]];
$this->params['breadcrumbs'][] = $this->title;

//Gadget::preview($ticket);
?>

<style>
    .modal-backdrop {
        z-index: 0 !important;
        --bs-backdrop-zindex: 0 !important;
    }
</style>

<?php if ($actives) { ?>
    <hr class="mt-5">
    <p><b>پکیج‌های فعال</b></p>
    <div class="row">
    <?php foreach ($actives as $item) { ?>
        <div class="col-md-4 col-12 bg-white border p-3 mb-2">
            <p><b>پکیج : </b><span><?= $item['package'] ?></span></p>
            <p>
                <?php foreach ($item['courses'] as $course) { ?>
                    <span><b><?= $course['name'] ?></b> / </span>
                <?php } ?>
            </p>
        </div>
    <?php } ?>
    </div>
<?php } ?>

<?php if ($answers) { ?>
    <hr class="mt-5">
    <div class="container">
        <div class="row">
            <?php foreach ($answers as $answer) { ?>
                <div class="col-md-6 col-12">
                    <p>
                        <b><?= $answer['question']['title'] ?> : </b>
                        <?php if ($answer['question']['type'] == Questions::TYPE_TEXT || $answer['question']['type'] == Questions::TYPE_NUMBER) { ?>
                            <?php if ($answer['response']) { ?>
                                <span><?= $answer['response'] ?></span>
                            <?php }else { ?>
                                <span class="text-danger">بدون پاسخ</span>
                            <?php } ?>
                        <?php }else { ?>
                            <?php foreach ($answer['options'] as $option) { ?>
                                <?php if ($option['content']) { ?>
                                    <span>
                                    <?php foreach ($option['content'] as $item) { ?>
                                        <?= $item['content'] ?> /
                                    <?php } ?>
                                    </span>
                                <?php }else { ?>
                                    <span class="text-danger">بدون پاسخ</span>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </p>
                </div>
            <?php } ?>
        </div>
    </div>
    <hr>
<?php } ?>

<div class="container mt-5">
    <div class="row">
        <div class="col-md-6 col-12">
            <div class="chat-section">
                <div class="chat-box-header">
                    <div class="col-8">
                        <div class="grp-info">
                            <h3 class="grp-name"><?= $this->title.'-'.$user->username ?></h3>
                        </div>
                    </div>
                    <div class="col-4">
                        <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#referenceTo" style="float: left">ارجاع پیام</button>
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
        </div>
        <div class="col-md-6 col-12">


            <div class="row bg-white p-3 border rounded">

                    <div class="audio-recorder">
                        <button class="btn btn-primary" id="start-recording">شروع ضبط</button>
                        <button class="btn btn-danger" id="stop-recording">پایان ضبط</button>
                    </div>

                    <audio id="recorded-audio" class="mt-4" controls></audio>

                    <div id="upload-form">
                        <div class="form-group text-center mt-3">
                            <button class="btn btn-success" onclick="upload()">آپلود</button>
                        </div>
                    </div>

                </div>
            <div class="tickets-form">

                <?php $form = ActiveForm::begin(); ?>

                <?= $form->field($model, 'text')->widget(TinyMce::className(), [
                    'options' => ['rows' => 6],
                    'language' => 'fa',
                    'clientOptions' => [
                        'plugins' => [
                            "advlist autolink lists link charmap print preview anchor",
                            "searchreplace visualblocks code fullscreen",
                            "insertdatetime media table contextmenu paste",
                            "link"
                        ],
                        'toolbar' => "undo redo | styleselect | bold italic | link | hyperlink | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent"
                    ]
                ]); ?>
                <div class="form-group text-center mt-3">
                    <?= Html::submitButton(Yii::t('app', 'ارسال'), ['class' => 'btn btn-success']) ?>
                </div>

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

<div id="referenceTo" class="modal fade" data-bs-backdrop="static"
     data-bs-keyboard="false" tabindex="-1" aria-labelledby="referenceToLabel" aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-6"><?= $form->field($reference, 'referenceCount')->textInput() ?></div>
                    <div class="col-6"><?= $form->field($reference, 'referenceTo')->dropDownList(Tickets::getType(), ['prompt' => 'لطفا انتخاب کنید']) ?></div>

                    <div class="form-group text-center mt-3">
                        <?= Html::submitButton(Yii::t('app', 'ارجاع'), ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">بستن</button>
            </div>
        </div>
    </div>

</div>

<script>
    const startRecordingButton = document.getElementById('start-recording');
    const stopRecordingButton = document.getElementById('stop-recording');
    const recordedAudio = document.getElementById('recorded-audio');
    const audioFileInput = document.getElementById('audio-file');
    const uploadButton = document.querySelector('#upload-form button');

    let mediaRecorder;
    let audioChunks = [];

    startRecordingButton.addEventListener('click', () => {
        navigator.mediaDevices.getUserMedia({ audio: true })
            .then(stream => {
                mediaRecorder = new MediaRecorder(stream);

                mediaRecorder.ondataavailable = event => {
                    if (event.data.size > 0) {
                        audioChunks.push(event.data);
                    }
                };

                mediaRecorder.onstop = () => {
                    const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });
                    recordedAudio.src = URL.createObjectURL(audioBlob);

                    // اجازه آپلود فایل را فعال کنید
                    uploadButton.disabled = false;
                };

                mediaRecorder.start();
                startRecordingButton.disabled = true;
                stopRecordingButton.disabled = false;
            })
            .catch(error => console.error('خطا در دسترسی به میکروفون:', error));
    });

    stopRecordingButton.addEventListener('click', () => {
        if (mediaRecorder && mediaRecorder.state !== 'inactive') {
            mediaRecorder.stop();
            startRecordingButton.disabled = false;
            stopRecordingButton.disabled = true;
        }
    });

    // این بخش آپلود فایل را به صورت پیش‌فرض غیرفعال می‌کند
    uploadButton.disabled = true;

    audioFileInput.addEventListener('change', () => {
        const selectedFile = audioFileInput.files[0];
        if (selectedFile) {
            uploadButton.disabled = false;
        }
    });

    const uploadForm = document.getElementById('test');

    function upload() {
        console.log('asdasdasdasdasdadasdasd')

        if (audioChunks.length > 0) {

            const audioBlob = new Blob(audioChunks, { type: 'audio/wav' });

            // ایجاد فرم داده (form data)
            const formData = new FormData();
            formData.append('audio', audioBlob, 'recorded-audio.mp3');
            formData.append('id', <?= $ticket['id'] ?>);

            // ارسال فرم داده به فایل upload.php
            fetch('https://mahsaonlin.com/admin/guest/main/upload-audio', {
                method: 'POST',
                body: formData
            }).then(() => {
                location.reload();
            }).catch(error => console.error('خطا در آپلود فایل:', error));

            // const audioBlob = new Blob(audioChunks, { type: 'audio/mp3' });
            // console.log('audioBlob', audioBlob)
            // const filename = "recorded-audio.mp3";
            // const audioUrl = URL.createObjectURL(audioBlob);
            // const file = new File([audioUrl], filename, {type: 'audio/mp3'});
            // const dataTransfer = new DataTransfer();
            // dataTransfer.items.add(file);
            // audioFileInput.files = dataTransfer.files;
        }
    }
    uploadForm.addEventListener('click', () => {
        // e.preventDefault();
    });

</script>
