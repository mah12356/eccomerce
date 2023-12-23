<?php
/** @var $counseling backend\controllers\SiteController */
/* @var $this yii\web\View */

$this->registerCssFile('@web/css/counseling.css');

if ($counseling == null){?>
    <h1 class="text-center">هنوز متقاضی پیدا نشده</h1>
<?php }else{?>
    <div class="main">
        <table>
            <tr>
                <th class="text-center">نام و نام خانوادگی</th>
                <th class="text-center">شماره موبایل</th>
            </tr>

    <?php foreach ($counseling as $item){?>
            <tr>
                <td><?=$item['name']?></td>
                <td><?= $item['phone']?></td>
            </tr>
    <?php }?>

        </table>
    </div>
<?php }?>
