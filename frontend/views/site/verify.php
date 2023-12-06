<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var common\modules\main\models\Tokens $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\components\Gadget;


$this->title = 'دریافت کد تایید';
$url = Yii::$app->urlManager;
$clientOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-12 text-white mx-auto my-5">
            <h1 class="register-title">وارد کردن کد تایید</h1>
            <div class="register-box d-block mx-auto">
                <p class="content">کد تایید دریافتی توسط پیامک را وارد نمایید</p>
                <?php $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-8 mx-auto">
                        <?= $form->field($model, 'code') ?>
                    </div>
                </div>
                <div class="form-group text-center">
                    <?= Html::submitButton('ثبت', ['class' => 'btn btn-success submit-btn']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<?php if ($clientOs['belong'] != 'desktop') { ?>
<!--    <div class="download-link p-4">-->
<!--        <div class="row w-100">-->
<!--            <div class="col-3"><img class="w-75 h-auto" src="/upload/config/logo.png" alt=""></div>-->
<!--            <div class="col-8"><a href="--><?php //= $url->createUrl(['/site/download-app']) ?><!--" class="text-decoration-none text-white">دانلود اپلیکیشن مهساآنلاین</a></div>-->
<!--            <div class="col-1"><button>X</button></div>-->
<!--        </div>-->
<!--    </div>-->
<?php } ?>

