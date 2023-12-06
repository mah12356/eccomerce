<?php

/* @var $role common\models\User */

use common\components\Gadget;
use common\models\User;
use yii\grid\GridView;
use yii\helpers\Html;

if ($role == User::ROLE_ADMIN){
    $this->title = Yii::t('app', 'لیست مدیران');
    $this->params['breadcrumbs'][] = '/ '.$this->title;
}
if ($role == User::ROLE_AUTHOR){
    $this->title = Yii::t('app', 'لیست نویسندگان');
    $this->params['breadcrumbs'][] = '/ '.$this->title;
}
if ($role == User::ROLE_COACH){
    $this->title = Yii::t('app', 'لیست مربیان');
    $this->params['breadcrumbs'][] = '/ '.$this->title;
}
if ($role == User::ROLE_USER){
    $this->title = Yii::t('app', 'لیست شاگردان');
    $this->params['breadcrumbs'][] = '/ '.$this->title;
}
?>

<div>
    <h1><?= Html::encode($this->title) ?></h1>
    <p>
        <?php
        if ($role == User::ROLE_ADMIN){
            echo Html::a(Yii::t('app', 'افزودن مدیر'), ['create-user', 'role' => $role], ['class' => 'btn btn-success']);
        }
        if ($role == User::ROLE_AUTHOR){
            echo Html::a(Yii::t('app', 'افزودن نویسنده'), ['create-user', 'role' => $role], ['class' => 'btn btn-success']);
        }
        if ($role == User::ROLE_COACH){
            echo Html::a(Yii::t('app', 'افزودن مربی'), ['create-user', 'role' => $role], ['class' => 'btn btn-success']);
        }
        ?>
    </p>
  
  	<?php 
  	if ($role == User::ROLE_USER) {
      echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'name',
                'lastname',
                'mobile',

                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{delete}',
                    'buttons' => [
                        'delete' => function ($url, $model) {
                            if (Yii::$app->user->id == $model->id){
                                return null;
                            }
                            return Html::a(Yii::t('app', 'حذف'), ['delete-user', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
    	]);
    }else {
      	echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                'username',
                'name',
                'lastname',
                'email',
                'mobile',

                ['class' => 'yii\grid\ActionColumn',
                    'template' => '{update} {account} {course} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model) {
                            return Html::a(Yii::t('app', 'تغییر گذرواژه'), ['change-password', 'id' => $model->id], ['class' => 'btn btn-primary']);
                        },
                        'account' => function ($url, $model) {
                            if (Gadget::getRoleByUserId($model->id) == User::ROLE_COACH) {
                                return Html::a(Yii::t('app', 'اطلاعات تکمیلی'), ['/coach/view', 'id' => $model->id], ['class' => 'btn btn-success']);
                            }else {
                                return null;
                            }
                        },
                        'course' => function ($url, $model) {
                            if (Gadget::getRoleByUserId($model->id) == User::ROLE_COACH) {
                                return Html::a(Yii::t('app', 'لیست کلاس ها'), ['/course/index', 'coach_id' => $model->id], ['class' => 'btn btn-info']);
                            }else {
                                return null;
                            }
                        },
                        'delete' => function ($url, $model) {
                            if (Yii::$app->user->id == $model->id){
                                return null;
                            }
                            return Html::a(Yii::t('app', 'حذف'), ['delete-user', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
    	]);
    } ?>
</div>
