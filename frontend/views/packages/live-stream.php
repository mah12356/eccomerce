<?php

/** @var $model common\models\Sections */
/** @var $comments common\models\Community */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;


header('Location: http://izaddad.com/site/live?user_id='. Yii::$app->user->id  .'&section_id='. $model['id'] .'&hls='. $model['hls']);
exit;

//header('Location: http://izaddad.com/upload/index1.php?hls=https://azhman.azhman.online:1935/azhman2/section-5-85/playlist.m3u8');
//exit;
if ($model['id'] == 85) {
//    header('Location: http://izaddad.com/upload/index1.php?hls='. $model['hls']);
//    exit;
    header('Location: http://izaddad.com/site/live?user_id='. Yii::$app->user->id  .'&section_id='. $model['id'] .'&hls='. $model['hls']);
    exit;
}else {
    header('Location: http://izaddad.com/upload/index1.php?hls='. $model['hls']);
    exit;
}



$this->registerCssFile('https://cdn.plyr.io/3.6.4/plyr.css');
$this->registerCss('
  .video-container {
	width: 100%;
    max-width: 800px;
    height: auto;
    margin: 0 auto;
  }
  .comment-form {
    margin-top: 20px;
    display: flex;
    justify-content: center;
    align-items: center;
    border-top: 1px solid #ccc;
    padding-top: 20px;
  }
  .comment-input {
    flex: 1;
    padding: 10px;
    border: none;
    border-radius: 20px;
    font-size: 14px;
    text-align: right;
    box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.1);
  }
  .comment-submit {
    margin-left: 10px;
    padding: 10px 20px;
    background-color: #007bff;
    border: none;
    border-radius: 20px;
    color: #fff;
    cursor: pointer;
    transition: background-color 0.3s;
  }
  .comment-submit:hover {
    background-color: #0056b3;
  }
');
$this->registerJsFile('https://cdn.jsdelivr.net/npm/hls.js@latest', [
    'position' => View::POS_END
]);
$this->registerJsFile('https://cdn.plyr.io/3.6.4/plyr.polyfilled.js', [
    'position' => View::POS_END
]);
$this->registerJsFile('@web/js/video-stream.js', [
    'position' => View::POS_END
]);

$this->title = $model['title'];
?>

<div class="container text-center" dir="ltr">
    <div class="video-container">
        <video id="videoPlayer" controls autoplay
               data-plyr-config='{ "controls": ["play-large", "play", "progress", "current-time", "mute", "volume", "fullscreen"], "keyboard": { "focused": true, "global": true } }'>
            <source src="http://azhman.azhman.online:1935/azhman2/section-5-84/playlist.m3u8" type="application/x-mpegURL">
        </video>
    </div>
</div>

<div class="container mt-5">
    <div id="success" class="alert alert-success" role="alert" style="display: none">پیام شما با موفقیت ثبت شد</div>
    <div id="failed" class="alert alert-success" role="alert" style="display: none">در ارسال پیام مشکلی رخ داده است</div>
    <div class="row">
        <?php $form = ActiveForm::begin(); ?>
        <div class="row">
            <div class="col-8 mx-auto">
                <?= $form->field($comments, 'text')->textInput(['id' => 'comment-text']) ?>
            </div>
        </div>
        <div class="form-group text-center">
            <?= Html::button('ثبت', [
                'class' => 'btn btn-success',
                'onclick' => '
                    $.post("' . Url::toRoute('/packages/send-comment') . '", 
                        { id : ' . $model['id'] . ', text : $("#comment-text").val() }
                    ).done(
                        function(data){
                            if (data === "") {
                                document.getElementById("success").style.display = "block";
                                document.getElementById("failed").style.display = "none";
                            }else {
                                document.getElementById("success").style.display = "none";                            
                                document.getElementById("failed").style.display = "block";
                                $("#failed").html(data);                            
                            }
                        }
                    )
                ',
            ]); ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
