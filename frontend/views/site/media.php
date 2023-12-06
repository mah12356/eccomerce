<?php

/** @var $model common\models\Media */

use common\components\Gadget;
use common\models\Media;
use yii\web\View;

$this->registerCssFile('@web/css/blogs.css');
$this->registerJsFile('@web/js/blogs.js', [
    'position' => View::POS_END
]);
?>

<div class="container mt-5 pt-5">
    <div class="row blog-card">
        <div class="header p-0">
            <?php
            if ($model['file'] != null) {
            ?>
            <video class="w-100 h-auto" controls poster="<?= Gadget::showFile($model['poster'], Media::UPLOAD_PATH) ?>">
                <source src="<?= Gadget::showFile($model['file'], Media::UPLOAD_PATH) ?>" type="video/mp4">
                Your browser does not support the video tag.
            </video>
                <?php
            }
            ?>
            <h1 class="text-center my-3"><?= $model['title'] ?></h1>
            <div class="my-5 p-1">
                <?= $model['description'] ?>
            </div>
        </div>
    </div>
</div>
