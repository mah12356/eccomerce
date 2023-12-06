<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\CourseSections $model */
/** @var common\models\Groups $groups */

$this->title = Yii::t('app', 'افزودن گروه جلسات به دوره');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', '/ مدیریت جلسات'), 'url' => ['index', 'course_id' => $model->course_id]];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="course-sections-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'groups' => $groups,
    ]) ?>

</div>
