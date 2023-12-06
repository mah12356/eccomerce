<?php
/** @var $model common\models\Packages */

use yii\helpers\Url;
use yii\web\View;

$this->registerJs('
    function findCourses(id) {
        $.get("'. Url::toRoute('/packages/find-package-courses') .'", 
            {package_id: id}
        ).done(
            function (data) {
                if (data) {
                    document.getElementById("course-section").style.display = "block";
                    document.getElementById("empty-course-section").style.display = "none";
                    
                    $("#course-section").html(data);
                }else {
                    document.getElementById("course-section").style.display = "none";
                    document.getElementById("empty-course-section").style.display = "block";
                }
            }
        )    
    }
', View::POS_END);

if ($model) {
    $this->registerJs('
        $(document).ready(function(){
            findCourses("'. $model[0]['id'] .'");
        });    
    ', View::POS_END);
}
?>


<div class="container panel-select-section">
    <div class="row">
        <div class="col-md-4 col-12"><p class="profile-subjects text-center">پکیج خود را انتخاب کنید</p></div>
        <div class="col-md-8 col-12">
            <form>
                <select class="form-select" onchange="findCourses(this.value)">
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

<div id="course-section" class="container mt-5" style="display: none"></div>
<div id="empty-course-section" class="container" style="display: none"></div>
