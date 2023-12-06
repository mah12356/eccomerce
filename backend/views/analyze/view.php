<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Analyze;
use common\models\Chats;
use dosamigos\tinymce\TinyMce;
use onmotion\apexcharts\ApexchartsWidget;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\web\View;
use yii\web\YiiAsset;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\User $user */
/** @var common\models\Analyze $model */
/** @var common\models\AnalyzeSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */
/** @var backend\controllers\AnalyzeController $weightPackage */
/** @var backend\controllers\AnalyzeController $bellyWaist */

$this->title = 'آنالیز بدنی : ' . $user->name . ' ' . $user->lastname;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ آنالیز بدنی'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
YiiAsset::register($this);

$url = Yii::$app->urlManager;

$this->registerCssFile('@web/css/swiper-bundle.min.css');
$this->registerJsFile('@web/js/swiper-bundle.min.js', [
    'position' => View::POS_END,
]);

$this->registerCssFile('@web/css/tickets.css');
$this->registerCss('
    .analyze-img {
        height: 500px;
        max-height: 500px;
        object-fit: contain;
    }
');
$this->registerJs('
    var swiper = new Swiper(".analyze-slider", {
        slidesPerView: 5,
        spaceBetween: 30,
        breakpoints: {
            // when window width is >= 320px
            320: {
                slidesPerView: 2,
                spaceBetween: 20
            },
            // when window width is >= 480px
            500: {
                slidesPerView: 3,
                spaceBetween: 30
            },
            1024: {
                slidesPerView: 5,
                spaceBetween: 30
            },
        },
    });
',);
?>
<div class="analyze-view">

    <h1><?= $this->title ?></h1>


    <div class="container mt-5">
        <div class="row">
            <div class="col-md-6 col-12 mb-3">
                <div class="chat-section">
                    <div class="chat-box-header">
                        <div class="col-8">
                            <div class="grp-info">
                                <h3 class="grp-name">تیکت آنالیز</h3>
                            </div>
                        </div>
                        <div class="col-4">
                            <!--                            <button type="button" class="btn btn-light" data-bs-toggle="modal" data-bs-target="#referenceTo" style="float: left">ارجاع پیام</button>-->
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
            <div class="col-md-6 col-12 mb-3">
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

                    <?= $form->field($chatModel, 'text')->widget(TinyMce::className(), [
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

                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-12"><?= $form->field($chatModel, 'imageFile')->fileInput() ?></div>
                        <div class="col-lg-6 col-md-6 col-12"><?= $form->field($chatModel, 'audioFile')->fileInput() ?></div>
                        <div class="col-lg-6 col-md-6 col-12"><?= $form->field($chatModel, 'videoFile')->fileInput() ?></div>
                        <div class="col-lg-6 col-md-6 col-12"><?= $form->field($chatModel, 'documentFile')->fileInput() ?></div>
                    </div>

                    <div class="form-group text-center mt-3">
                        <?= Html::submitButton(Yii::t('app', 'ارسال'), ['class' => 'btn btn-success']) ?>
                    </div>

                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-5 table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                [
                    'attribute' => 'package_id',
                    'content' => function ($model) {
                        return '<p>' . $model->package->name . '</p>';
                    }
                ],
                'height',
                'weight',
                'age',
                'wrist',
                'arm',
                'chest',
                'under_chest',
                'belly',
                'waist',
                'hip',
                'thigh',
                'shin',
                [
                    'attribute' => 'date',
                    'format' => 'html',
                    'content' => function ($model) {
                        return '<p>' . Jdf::jdate('d F Y', $model->updated_at) . '</p>';
                    }
                ],
            ],
        ]); ?>
    </div>

    <?php if ($model) { ?>
        <div class="row mt-5 mb-2">
            <p><b>عکس از جلو</b></p>
            <hr>
            <div class="swiper analyze-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($model as $item) { ?>
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="<?= Gadget::showFile($item['front_image'], Analyze::UPLOAD_PATH) ?>" alt=""
                                     class="card-img-top analyze-img">
                                <div class="card-body text-center">
                                    <b><?= Jdf::jdate('d F Y', $item['updated_at']) ?></b>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row mt-5 mb-2">
            <p><b>عکس از کنار</b></p>
            <hr>
            <div class="swiper analyze-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($model as $item) { ?>
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="<?= Gadget::showFile($item['side_image'], Analyze::UPLOAD_PATH) ?>" alt=""
                                     class="card-img-top analyze-img">
                                <div class="card-body text-center">
                                    <b><?= Jdf::jdate('d F Y', $item['updated_at']) ?></b>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div class="row mt-5 mb-2">
            <p><b>عکس از پشت</b></p>
            <hr>
            <div class="swiper analyze-slider">
                <div class="swiper-wrapper">
                    <?php foreach ($model as $item) { ?>
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="<?= Gadget::showFile($item['back_image'], Analyze::UPLOAD_PATH) ?>" alt=""
                                     class="card-img-top analyze-img">
                                <div class="card-body text-center">
                                    <b><?= Jdf::jdate('d F Y', $item['updated_at']) ?></b>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    <?php } ?>

    <?php if ($model) { ?>
        <div class="row mt-5">
            <hr>
            <div class="col-lg-6 col-12">
                <p class="text-center"><b>نمودار وزن به پکیج</b></p>
                <?= ApexchartsWidget::widget([
                    'type' => 'line',
                    'chartOptions' => [
                        'chart' => [
                            'toolbar' => [
                                'show' => false,
                            ],
                        ],
                        'tooltip' => [
                            'enabled' => false, // Disable tooltips
                        ],
                        'xaxis' => [
                            'type' => 'category',
                            'categories' => $weightPackage['categories'],
                        ],
                        'plotOptions' => [
                            'bar' => [
                                'horizontal' => true,
                                'endingShape' => 'rounded'
                            ],
                        ],
                        'dataLabels' => [
                            'enabled' => true
                        ],
                        'stroke' => [
                            'show' => true,
                            'curve' => 'smooth',
                        ],
                    ],
                    'series' => $weightPackage['series']
                ]); ?>
            </div>
            <div class="col-lg-6 col-12">
                <p class="text-center"><b>نمودار دور کمر به دور شکم</b></p>
                <?= ApexchartsWidget::widget([
                    'type' => 'line',
                    'chartOptions' => [
                        'chart' => [
                            'toolbar' => [
                                'show' => false,
                            ],
                        ],
                        'tooltip' => [
                            'enabled' => false, // Disable tooltips
                        ],
                        'xaxis' => [
                            'type' => 'category',
                            'categories' => $bellyWaist['categories'],
                        ],
                        'plotOptions' => [
                            'bar' => [
                                'horizontal' => true,
                                'endingShape' => 'rounded'
                            ],
                        ],
                        'dataLabels' => [
                            'enabled' => true
                        ],
                        'stroke' => [
                            'show' => true,
                            'curve' => 'smooth',
                        ],
                    ],
                    'series' => $bellyWaist['series']
                ]); ?>
            </div>
        </div>
    <?php } ?>
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