<?php
/** @var yii\web\View $this */
/** @var common\models\Factor $model */

use common\components\Gadget;
use common\components\Jdf;
use common\models\Factor;
use yii\helpers\Html;
use yii\web\View;

$this->title = Yii::t('app', 'لیست فاکتور ها');

$this->registerCssFile('@web/css/factors.css');
$this->registerJsFile('@web/js/factors.js', [
    'position' => View::POS_END
]);

$url = Yii::$app->urlManager;
?>

<?php if ($model) { ?>
    <h1><?= $this->title ?></h1>
    <div class="container">
        <?php foreach ($model as $item) { ?>
            <div class="row factor-pane">
                <div class="col-md-4"><p class="factor-id py-3"><span>شناسه فاکتور : </span><span><?= Gadget::convertToPersian($item['id']) ?></span></p></div>
                <div class="col-md-3"><p class="m-0 py-3"><span>تاریخ صدور : </span><span><?= Jdf::jdate('Y/m/d', $item['publish_date']) ?></span></p></div>
                <div class="col-md-3">
                    <?php switch ($item['payment']) {
                        case Factor::PAYMENT_PENDING:
                            echo '<p class="factor-status-pending m-0 py-3">در انتظار پرداخت</p>';
                            break;
                        case Factor::PAYMENT_ACCEPT:
                            echo '<p class="factor-status-accept m-0 py-3">پرداخت شده</p>';
                            break;
                        case Factor::PAYMENT_REJECT:
                            echo '<p class="factor-status-reject m-0 py-3">پرداخت نا موفق</p>';
                            break;
                        default:
                            echo '<p class="factor-status-pending m-0 py-3">نا مشخص</p>';
                            break;
                    } ?>
                </div>
                <div class="col-md-2"><button id="<?= $item['id'] ?>" class="accordion py-3">جزئیات</button></div>
                <div id="panel_<?= $item['id'] ?>" class="panel">
                    <div class="container-fluid info-section py-3">
                        <div class="row">
                            <div class="col-md-3"><p class="subjects">نوع پرداخت</p></div>
                            <div class="col-md-3"><p class="subjects">نام پکیج</p></div>
                            <div class="col-md-3"><p class="subjects">قیمت</p></div>
                            <div class="col-md-3"><p class="subjects">آخرین تغییر</p></div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <?php switch ($item['method']) {
                                    case Factor::METHOD_OFFLINE:
                                        echo '<p class="content">آفلاین</p>';
                                        break;
                                    case Factor::METHOD_ONLINE:
                                        echo '<p class="content">آنلاین</p>';
                                        break;
                                    default:
                                        echo '<p class="content">نا مشخص</p>';
                                        break;
                                } ?>
                            </div>
                            <div class="col-md-3"><p class="content"><?= $item['register']['package']['name'] ?></p></div>
                            <div class="col-md-3"><p class="content"><?= $item['amount'] ?></p></div>
                            <div class="col-md-3"><p class="content"><?= Jdf::jdate('Y/m/d', $item['update_date']) ?></p></div>
                        </div>
                    </div>
                    <?php if ($item['payment'] == Factor::PAYMENT_PENDING) { ?>
                        <p class="mt-4">
                            <a class="btn btn-success" href="<?= $url->createUrl(['/factor/pay-factor', 'id' => $item['id']]) ?>">پرداخت</a>
                            <?= Html::a(Yii::t('app', 'انصراف'), ['/factor/delete', 'id' => $item['id']], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]) ?>
                        </p>
                    <?php } ?>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>