<?php

/** @var $section frontend\controllers\SiteController */

?>

<div class="container mt-5">
    <div class="row">
        <?php
        switch ($section) {
            case 'package':
                ?>
                <video class="w-100 h-auto" controls>
                    <source src="/upload/statics/package.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php
                break;
            case 'live':
                ?>
                <video class="w-100 h-auto" controls>
                    <source src="/upload/statics/live.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php
                break;
            case 'diet':
                ?>
                <video class="w-100 h-auto" controls>
                    <source src="/upload/statics/regimes.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php
                break;
            case 'messenger':
                ?>
                <video class="w-100 h-auto" controls>
                    <source src="/upload/statics/messenger.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <?php
                break;
        }
        ?>
    </div>
</div>


