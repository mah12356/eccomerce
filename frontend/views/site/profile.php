<?php

/** @var $model common\models\User */

?>

<div class="row">
    <div class="col-lg-3 col-md-6 col-10 mx-auto">
        <div class="row">
            <div class="col-5"><p class="profile-subjects">نام</p></div>
            <div class="col-7"><p class="profile-content-box"><?= $model['name'] ?></p></div>
        </div>
        <div class="row">
            <div class="col-5"><p class="profile-subjects">نام خانوادگی</p></div>
            <div class="col-7"><p class="profile-content-box"><?= $model['lastname'] ?></p></div>
        </div>
        <div class="row">
            <div class="col-5"><p class="profile-subjects">شماره موبایل</p></div>
            <div class="col-7"><p class="profile-content-box"><?= $model['mobile'] ?></p></div>
        </div>
    </div>
</div>
