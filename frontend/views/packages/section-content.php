<?php

/** @var $model common\models\Sections */

use common\components\Gadget;
use common\components\Jdf;

$this->title = Gadget::convertToPersian($model['title']);
?>

<div class="container">
    <h1><?= Gadget::convertToPersian($model['title']) ?></h1>
    <?php foreach ($model['content'] as $item) { ?>
        <div class="row mb-5">
            <div class="col-lg-4 col-md-6 col-12 d-block mx-auto">
                <video id="content-<?= $item['id'] ?>" class="w-100 h-auto d-block mx-auto" controls>
                    <source src="<?= Gadget::showFile($item['file'], 'sections/' . $model['group']) ?>" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <button onclick="fullScreen('content-<?= $item['id'] ?>')" class="d-block w-100 mx-auto btn btn-success" style="border-radius: 0">
                    <i class="fa fa-crosshairs ms-4"></i><span>بزرگنمایی</span>
                </button>
            </div>
        </div>
    <?php } ?>
</div>

<script>
    function fullScreen(target) {
        const video = document.getElementById(target);

        if (!document.fullscreenElement) {
            video.requestFullscreen()
                .catch(err => {
                    alert(`Error attempting to enable fullscreen mode: ${err.message}`);
                });
        } else {
            document.exitFullscreen();
        }
    }
</script>
