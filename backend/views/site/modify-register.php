<?php
/** @var common\models\User $user */
/** @var common\models\Register $register */
/** @var common\models\Packages $package */
/** @var common\models\Course $current */
/** @var common\models\Course $remain */

use common\models\Course;
$url = Yii::$app->urlManager;
$this->title = 'ویرایش : ' . $package->name;
?>

<h1><?= $this->title ?></h1>

<p class="mt-5"><b>دوره های فعال</b></p>
<div class="row">
    <?php foreach ($current as $item) { ?>
        <div class="col-md-2">
            <p>
                <span class="btn btn-secondary"><?= $item['name'] ?></span>
                <?php if ($item['required'] == Course::REQUIRED_FALSE) { ?>
                    <a href="<?= $url->createUrl(['/site/modify', 'register_id' => $register->id, 'package_id' => $package->id, 'course_id' => $item['id'], 'action' => 'remove']) ?>" class="text-danger me-2">
                        <i class="fa fa-trash"></i>
                        حذف
                    </a>
                <?php } ?>
            </p>
        </div>
    <?php } ?>
</div>

<?php if ($remain) { ?>
<p class="mt-5"><b>سایر دوره های موجود</b></p>
<div class="row">
    <?php foreach ($remain as $item) { ?>
        <div class="col-md-2">
            <p>
                <span class="btn btn-secondary"><?= $item['name'] ?></span>
                <a href="<?= $url->createUrl(['/site/modify', 'register_id' => $register->id, 'package_id' => $package->id, 'course_id' => $item['id'], 'action' => 'add']) ?>" class="text-success me-2">
                    <i class="fa fa-plus-circle"></i>
                    افزودن
                </a>
            </p>
        </div>
    <?php } ?>
</div>
<?php } ?>