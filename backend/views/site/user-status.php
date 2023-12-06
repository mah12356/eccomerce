<?php
/** @var common\models\User $user */
/** @var common\models\Packages $packages */
/** @var common\models\Register $model */

/** @var common\models\Factor $actives */

use common\components\Jdf;
use common\models\Diet;
use yii\bootstrap5\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\View;

$this->title = 'وضعیت : ' . $user['name'] . ' ' . $user['lastname'];

$url = Yii::$app->urlManager;
$this->registerJs('
    function findCourses(id) {
        $.get("' . Url::toRoute('/site/get-courses') . '", 
            { id : id }
        ).done(
            function(data){
                $("#courses").html(data);
            }
        )
    }
', View::POS_END)
?>

    <h1><?= $user['name'] ?> <?= $user['lastname'] ?></h1>

    <div class="row">
        <?php $form = ActiveForm::begin(); ?>

        <div class="row">
            <div class="col-md-3 col-12">
                <?= $form->field($model, 'package_id')->dropDownList(ArrayHelper::map($packages, 'id', 'name'),
                    ['prompt' => 'لطفا انتخاب کنید', 'onchange' => 'findCourses(this.value)'])->label('پکیج') ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 col-12">
                <div class="form-group" id="courses"></div>
            </div>
        </div>

        <div class="form-group mt-3">
            <?= Html::submitButton(Yii::t('app', 'ثبت نام'), ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

<?php if ($actives) { ?>
    <hr class="my-4">

    <p><b>پکیج‌های کاربر</b></p>

    <div class="row">
        <?php foreach ($actives as $item) { ?>
            <div class="col-md-4 col-12 border bg-white m-3 p-3">
                <div class="row">
                    <div class="col-6"><p><b>شناسه فاکتور : </b><span><?= $item['id'] ?></span></p></div>
                    <div class="col-6"><p><b>تاریخ پرداخت : </b><span><?= Jdf::jdate('Y/m/d', $item['update_date']) ?></span></p></div>
                </div>
                <p><b>پکیج : </b><span><?= $item['register']['package']['name'] ?></span></p>
                <p><b>دوره های فعال شده</b></p>
                <p class="m-0">
                    <?php foreach ($item['register']['courses'] as $index) { ?>
                        <button class="btn btn-secondary"><?= $index['course']['name'] ?></button>
                    <?php } ?>
                    <?php
                    $diet = Diet::find()->where(['user_id' => $item['user_id'], 'package_id' => $item['register']['package']['id']])->asArray()->one();
                    if ($diet) {
                        echo '<button class="btn btn-secondary">برنامه غذایی</button>';
                    }
                    ?>
                </p>
                <p class="my-3">
                    <a class="btn btn-primary" href="<?= $url->createUrl(['/site/modify-register', 'id' => $item['id']]) ?>">ویرایش</a>
                </p>
            </div>
        <?php } ?>
    </div>
<?php }

