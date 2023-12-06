<?php
/** @var $model common\models\Packages */

use yii\helpers\Url;
use yii\web\View;

$this->registerJs('
    function findDiets(id) {
        $.get("'. Url::toRoute('/diet/find-package-diets') .'", 
            {package_id: id}
        ).done(
            function (data) {
                if (data) {
                    document.getElementById("diet-section").style.display = "block";
                    document.getElementById("empty-diet-section").style.display = "none";
                    
                    $("#diet-section").html(data);                
                }else {
                    document.getElementById("diet-section").style.display = "none";
                    document.getElementById("empty-diet-section").style.display = "block";
                }
            }
        )    
    }
', View::POS_END);

if ($model) {
    $this->registerJs('
        $(document).ready(function(){
            findDiets("'. $model[0]['id'] .'");
        });    
    ', View::POS_END);
}
?>


<div class="container panel-select-section">
    <div class="row">
        <div class="col-md-4 col-12"><p class="profile-subjects text-center">پکیج خود را انتخاب کنید</p></div>
        <div class="col-md-8 col-12">
            <form>
                <select class="form-select" onchange="findDiets(this.value)">
                    <?php if ($model) { ?>
                        <?php foreach ($model as $item) { ?>
                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                        <?php } ?>
                    <?php }else { ?>
                        <option selected disabled>در حال حاضر شما هیچ پکیجی ندارید</option>
                    <?php } ?>
                </select>
            </form>
        </div>
    </div>
</div>

<div id="diet-section" class="container mt-5" style="display: none"></div>
<div id="empty-diet-section" class="container mt-5" style="display: none"></div>
