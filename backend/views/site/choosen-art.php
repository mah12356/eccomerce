<?php
/** @var yii\bootstrap5\ActiveForm $form */

use yii\bootstrap5\ActiveForm;
use yii\helpers\Html;
?>
<section class="row">
<?php foreach ($articles as $art){?>
    <div class="card col-md-4 col-12">
        <img class="card-img-top" src="/upload/article/<?= $art['banner']?>" alt="Card image cap">
        <div class="card-body">
            <h5 class="card-title"><?= $art['title']?></h5>
            <?php $form = ActiveForm::begin();?>
            <input type="hidden" name="id" value="<?= $art['id']?>">
            <input type="hidden" name="category_id" value="<?= $art['category_id']?>">
            <input type="hidden" name="banner" value="<?= $art['banner']?>">
            <input type="hidden" name="title" value="<?= $art['title']?>">
            <input type="hidden" name="date" value="<?= $art['modify_date']?>">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app', 'فعال یا غیر فعال'), ['class' => 'btn btn-success']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php }?>
</section>
