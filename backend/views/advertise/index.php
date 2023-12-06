<?php

use common\models\Analyze;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var array $count */

$this->title = Yii::t('app', 'تبلیغات');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
$url = Yii::$app->urlManager
?>
<div class="w-100 d-flex direction-column justify-content-around align-items-center" style="height: 30vh;flex-direction: column">
    <a href="<?=$url->createUrl(['/advertise/send_with_file'])?>" class="btn btn-success">افزودن به لیست از طریق لیست شماره ها</a>
    <a href="<?=$url->createUrl(['/advertise/send_with_package'])?>" class="btn btn-success">افزودن به لیست از طریق لیست پکیج ها</a>
</div>
<div>
    <p>تاکنون تعداد <?= $count[0]?> پیام فرستاده شده است </p>
    <p>تاکنون تعداد <?= $count[1]?> پیام در صف ارسال است </p>

    <p></p>
</div>
<div class="w-100 d-flex  justify-content-around align-items-center" style="height: 50vh;">
    <a href="<?=$url->createUrl(['/advertise/delete_list'])?>" class="btn btn-danger">حذف باقی مانده لیست </a>
    <a href="<?=$url->createUrl(['/advertise/send_sms'])?>" class="btn btn-success">ارسال پیامک ها</a>
</div>
