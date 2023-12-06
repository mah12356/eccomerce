<?php

/** @var $model common\models\Diet */
/** @var $questions common\models\Questions */


use common\components\Gadget;
use common\models\Questions;
use yii\bootstrap5\ActiveForm;
use yii\web\View;

//Gadget::preview($questions);

$this->registerJs("
(function () {
  'use strict'
  var forms = document.querySelectorAll('.needs-validation')

  // Loop over them and prevent submission
  Array.prototype.slice.call(forms)
    .forEach(function (form) {
      form.addEventListener('submit', function (event) {
        if (!form.checkValidity()) {
          event.preventDefault()
          event.stopPropagation()
        }

        form.classList.add('was-validated')
      }, false)
    })
})()
", View::POS_END);
?>

<div class="container">

    <h1><?= $this->title ?></h1>
    <form action="/diet/get-regime?id=<?= $model->id ?>" method="post" class="row g-3 needs-validation" novalidate>
        <input type="hidden" name="_csrf-frontend" value="eK71ON-lTJjfCJ6EVoa9Gfx6zfmlTGsXtw-ovFVUo7IRxoxykMkOr7VH29AGyfEoxT6CvMl1I37VZ8DrFzDm1A==">
        <?php foreach ($questions as $item) {
            switch ($item['type']) {
                case Questions::TYPE_TEXT:
        ?>
                    <div class="col-md-4">
                        <label for="question_<?= $item['id'] ?>" class="form-label"><b><?= $item['title'] ?></b></label>
                        <?php if ($item['required'] == Questions::REQUIRED_TRUE) { ?>
                            <input id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>" type="text" class="form-control" required>
                        <?php }else { ?>
                            <input id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>" type="text" class="form-control">
                        <?php } ?>
                    </div>
        <?php
                    break;
                case Questions::TYPE_NUMBER:
        ?>
                    <div class="col-md-4">
                        <label for="question_<?= $item['id'] ?>" class="form-label"><b><?= $item['title'] ?></b></label>
                        <?php if ($item['required'] == Questions::REQUIRED_TRUE) { ?>
                            <input id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>" type="number" class="form-control" required>
                        <?php }else { ?>
                            <input id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>" type="number" class="form-control">
                        <?php } ?>
                    </div>
        <?php
                    break;
                case Questions::TYPE_DROPDOWN:
        ?>
                    <div class="col-md-4">
                        <label for="question_<?= $item['id'] ?>" class="form-label"><b><?= $item['title'] ?></b></label>
                        <?php if ($item['required'] == Questions::REQUIRED_TRUE) { ?>
                            <select class="form-select" id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>[]" required>
                                <option selected disabled value="">لطفا انتخاب کنید</option>
                                <?php foreach ($item['options'] as $option) { ?>
                                    <option value="<?= $option['id'] ?>"><?= $option['content'] ?></option>
                                <?php } ?>
                            </select>
                        <?php }else { ?>
                            <select class="form-select" id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>[]">
                                <option selected disabled value="">لطفا انتخاب کنید</option>
                                <?php foreach ($item['options'] as $option) { ?>
                                    <option value="<?= $option['id'] ?>"><?= $option['content'] ?></option>
                                <?php } ?>
                            </select>
                        <?php } ?>
                    </div>
        <?php
                    break;
                case Questions::TYPE_CHECKBOX:
        ?>
                    <div class="col-12">
                        <p><b><?= $item['title'] ?></b></p>
                        <?php foreach ($item['options'] as $option) { ?>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="checkbox" id="question_<?= $option['id'] ?>" name="q_<?= $item['id'] ?>[]" value="<?= $option['id'] ?>">
                                <label class="form-check-label" for="question_<?= $option['id'] ?>"><?= $option['content'] ?></label>
                            </div>
                        <?php } ?>
                    </div>
        <?php
                    break;
                case Questions::TYPE_RADIO:
        ?>
                    <div class="col-12">
                        <p><b><?= $item['title'] ?></b></p>
                        <?php if ($item['required'] == Questions::REQUIRED_TRUE) { ?>
                            <?php foreach ($item['options'] as $option) { ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>[]" value="<?= $option['id'] ?>" required>
                                    <label class="form-check-label" for="question_<?= $item['id'] ?>"><?= $option['content'] ?></label>
                                </div>
                            <?php } ?>
                        <?php }else { ?>
                            <?php foreach ($item['options'] as $option) { ?>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" id="question_<?= $item['id'] ?>" name="q_<?= $item['id'] ?>[]" value="<?= $option['id'] ?>">
                                    <label class="form-check-label" for="question_<?= $item['id'] ?>"><?= $option['content'] ?></label>
                                </div>
                            <?php } ?>
                        <?php } ?>
                    </div>
        <?php
                    break;
            }
        } ?>

        <div class="col-12">
            <button class="btn btn-primary" type="submit">ثبت</button>
        </div>
    </form>
</div>