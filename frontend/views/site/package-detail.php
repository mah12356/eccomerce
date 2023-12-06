<?php

use common\components\Gadget;
use common\components\Jdf;
use common\models\Packages;
use yii\bootstrap5\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Packages $model */

$this->title = $model['name'];
$this->registerCssFile('@web/css/packages.css');

$url = Yii::$app->urlManager;

$price = 0;
foreach ($model['courses'] as $item) {
    $price += (int)$item['price'];
}
?>

<section class="p-4">
    <div class="content-box">
        <h1><?= $model['name'] ?></h1>
        <div class="container">
            <div class="row">
                <div class="col-md-5 col-12 mb-5">
<!--                    -->
                    <?php if (Gadget::fileExist($model['poster'], Packages::UPLOAD_PATH) && !Gadget::fileExist($model['video'], Packages::UPLOAD_PATH)) { ?>
                        <img class="package-poster px-5" src="<?= Gadget::showFile($model['poster'], Packages::UPLOAD_PATH) ?>" alt="<?= $model['alt'] ?>">
                    <?php } else { ?>
                        <video class="w-100 h-auto" controls>
                            <source src="<?= Gadget::showFile($model['video'], Packages::UPLOAD_PATH) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>

                    <?php } ?>
                </div>
                <div class="col-md-7 col-12 mb-5">
                    <div class="package-description"><?= $model['description'] ?></div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 col-12 mb-3 d-block mx-auto">
                    <p class="package-info-title">قیمت پکیج</p>
                    <div class="price-box">
                        <?php if ($model['discount']) { ?>
                            <div class="row">
                                <?php if($model['discount'] > 100) { ?>
                                    <div class="col-4"><p class="discount">
                                            <?= Gadget::convertToPersian(number_format($model['discount'])) ?>
                                            <span class="d-block">تومان تخفیف</span>
                                        </p></div>
                                <?php }else { ?>
                                    <div class="col-4"><p class="discount">%<?= Gadget::convertToPersian($model['discount']) ?> تخفیف</p></div>
                                <?php } ?>
                                <div class="col-8">
                                    <p class="discount-price"><del><?= Gadget::convertToPersian(number_format($price)) ?> تومان</del></p>
                                    <p class="package-price"><?= Gadget::convertToPersian(number_format(Gadget::calculateDiscount($price, $model['discount']))) ?> تومان</p>
                                </div>
                            </div>
                        <?php }else { ?>
                            <div class="row">
                                <div class="col-12">
                                    <p class="package-price"><?= Gadget::convertToPersian(number_format($price)) ?> تومان</p>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <div class="text-center mt-5">
                <?php $form = ActiveForm::begin(['action' => '/factor/buy-package']); ?>
                <input type="hidden" name="package_id" value="<?= $model['id'] ?>">
                <table class="d-none">
                    <?php foreach ($model['courses'] as $item) { ?>
                        <tr>
                            <td><?= $item['name'] ?></td>
                            <td>
                                <div>
                                    <?php if ($item['required']) { ?>
                                        <input type="hidden" name="courses[]" value="<?= $item['id'] ?>" checked />
                                    <?php }else { ?>
                                        <input type="hidden" name="courses[]"  value="<?= $item['id'] ?>" checked />
                                    <?php } ?>
                                    <span></span>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                    <tr>
                        <td colspan="2">

                        </td>
                    </tr>
                </table>
                    <input type="submit" value="خرید پکیج" >
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</section>
