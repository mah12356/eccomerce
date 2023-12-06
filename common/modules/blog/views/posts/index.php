<?php

use common\components\Gadget;
use common\modules\blog\models\Gallery;
use common\modules\blog\models\Posts;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var common\modules\blog\controllers\PostsController $data */
/** @var common\modules\blog\models\PostsSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = Yii::t('app', 'لیست نوشته‌ها');
$this->params['breadcrumbs'][] = ' / ' . $this->title;
?>
<div class="posts-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'افزودن متن'), ['create', 'page_id' => $data['page_id'], 'belong' => $data['belong']], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'title',
            'text:ntext',
            [
                'format' => 'html',
                'headerOptions' => ['style' => 'color: #0d6efd'],
                'label' => 'فایل',
                'value' => function ($model) {
                    if (Gadget::fileExist($model->banner, Posts::UPLOAD_PATH)) {
                        $extension = explode('.', $model->banner);
                        if ($extension[1] == 'mp4') {
                            return $model->banner;
                        }else {
                            return '<img src="'. Gadget::ShowFile($model->banner, Posts::UPLOAD_PATH) .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                        }
                    }else {
                        return '<img src="'. Gadget::ShowFile($model->banner, Posts::UPLOAD_PATH) .'" alt="'. $model->alt .'" class="gridview-banner center-block">';
                    }
                },
            ],

            ['class' => 'yii\grid\ActionColumn',
                'template' => '{update} {gallery} {delete}',
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'ویرایش'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary my-2']);
                    },
                    'gallery' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'گالری'), ['/blog/gallery/index', 'parent_id' => $model->id, 'belong' => Gallery::BELONG_POSTS], ['class' => 'btn btn-warning my-2']);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a(Yii::t('app', 'حذف'), ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger my-2',
                            'data' => [
                                'confirm' => Yii::t('app', 'آیا مطمئن هستید ؟'),
                                'method' => 'post',
                            ],
                        ]);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
