<?php

/** @var $course common\models\Course */

use common\components\Jdf;
use yii\helpers\Url;
use yii\web\View;

$sections = \common\models\Sections::find()->where(['group' => 18])->orderBy(['date' => SORT_ASC])->asArray()->all();
//\common\components\Gadget::preview($sections);
$this->registerJs('
    function findSections(id) {
        $.get("'. Url::toRoute('/packages/find-course-sections') .'", 
            {id: id}
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

if ($course) {
    $this->registerJs('
        $(document).ready(function(){
            findSections("'. $course[0]['id'] .'");
        });    
    ', View::POS_END);
}

?>

<style>
    .progress-container {
        display: flex;
        justify-content: space-between;
        margin-bottom: 3rem;
        max-width: 100vw;
        position: relative;
        width: 100%;
    }

    .progress-container::before {
        background-color: #d1d2d4;
        content: "";
        height: 0.4rem;
        left: 0;
        position: absolute;
        top: 58%;
        transform: translateY(-50%);
        z-index: -1;
    }

    .progress {
        background-color: #b4b6b9;
        height: 0.2rem;
        left: 0;
        position: absolute;
        top: 58%;
        transform: translateY(-50%);
        transition: 0.4s ease;
        width: 100%;
        z-index: -1;
    }

    .step {
        align-items: center;
        background-color: #fff;
        border: 3px solid #d1d2d4;
        color: #000;
        justify-content: center;
        transition: 0.4s ease;
        z-index: -1;
        border-radius: 5px;
        padding: 10px;
        width: 40%;
        height: auto;
    }

    .step .section-title {
        font-size: 13px;
        text-align: center !important;
        height: 60px;
        overflow: hidden;
        font-weight: 700;
    }
    .step .section-date {
        font-size: 13px;
        text-align: center !important;
    }


    /*.step .section-title span {*/
    /*    font-size: 18px;*/
    /*    background: red;*/
    /*    color: #FFFFFF;*/
    /*    width: 75px;*/
    /*    height: 75px;*/
    /*    padding: 0 7px;*/
    /*    font-weight: 900;*/
    /*    border-radius: 50%;*/
    /*    margin-left: 5px;*/
    /*}*/

    .step.passed {
        border-color: #5221BD;
    }
    .step.current {
        border-color: green;
    }
    .step.remain {
        border-color: red;
    }
</style>

<div class="container panel-select-section mt-5">
    <div class="row">
        <div class="col-md-4 col-12"><p class="profile-subjects text-center">دوره خود را انتخاب کنید</p></div>
        <div class="col-md-8 col-12">
            <form>
                <select class="form-select" onchange="findSections(this.value)">
                    <?php if ($course) { ?>
                        <?php foreach ($course as $item) { ?>
                            <option value="<?= $item['id'] ?>"><?= $item['name'] ?></option>
                        <?php } ?>
                    <?php }else { ?>
                        <option selected disabled>در حال حاضر شما هیچ دوره ای ندارید</option>
                    <?php } ?>
                </select>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <div id="course-section" class="container mt-5" style="display: none"></div>
    <div id="empty-course-section" class="container" style="display: none"></div>
</div>