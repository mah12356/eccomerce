<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var common\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\components\Gadget;

$this->title = 'ورود / ثبت نام';
$url = Yii::$app->urlManager;
$clientOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-12 text-white mx-auto my-5">
            <h1 class="register-title">دریافت شماره موبایل</h1>
            <div class="register-box d-block mx-auto">
                <p class="content">شماره موبایل خود را وارد کنید</p>
                <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
                <div class="row">
                    <div class="col-8 mx-auto">
                        <?= $form->field($model, 'mobile')->textInput(['placeholder' => '---------۰۹']) ?>
                    </div>
                </div>
                <div class="form-group text-center">
                    <?= Html::submitButton('ثبت', ['class' => 'btn btn-success submit-btn']) ?>
                </div>
                <?php ActiveForm::end(); ?>
            </div>

            <p><b class="text-danger">اطلاعیه</b></p>
            <p class="text-dark"><b>لطفا برای رفع مشکلات اپلیکیشن و شرکت در کلاس های لایو اپلیکیشن مهساآنلاین را از طریق لینک زیر دانلود کنید</b></p>
            <p class="text-dark"><b>و پس از دانلود اپلیکیشن جدید، اپلیکیشن قدیمی را حذف و اپلیکیشن جدید را نصب کنید</b></p>
            <p class="text-dark"><a href="<?= $url->createUrl(['/site/download-app']) ?>">دانلود اپلیکیشن مهساآنلاین</a></p>
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

