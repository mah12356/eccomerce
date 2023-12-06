<?php

/** @var yii\web\View $this */
/** @var yii\bootstrap5\ActiveForm $form */

/** @var common\models\SignupForm $model */

use yii\bootstrap5\Html;
use yii\bootstrap5\ActiveForm;
use common\components\Gadget;


$this->title = 'ثبت نام';
$url = Yii::$app->urlManager;
$clientOs = Gadget::getClientOs($_SERVER['HTTP_USER_AGENT']);
?>
<div class="container">
    <div class="row">
        <div class="col-md-4 col-12 text-white mx-auto my-5">
            <h1 class="register-title">اطلاعات کاربری</h1>
            <div class="register-box d-block mx-auto">
                <p class="content">نام و نام‌خانوادگی خود را وارد کنید</p>
                <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>
                <div class="row">
                    <div class="col-8 mx-auto">
                        <div class="col-12"><?= $form->field($model, 'name')->textInput(['autofocus' => true, 'placeholder' => 'نام'])->label(false) ?></div>
                        <div class="col-12"><?= $form->field($model, 'lastname')->textInput(['placeholder' => 'نام خانوادگی'])->label(false) ?></div>
                    </div>
                </div>
                <div class="form-group text-center">
                    <?= Html::submitButton('ثبت نام', ['class' => 'btn btn-success submit-btn']) ?>
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

