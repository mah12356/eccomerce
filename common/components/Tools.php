<?php

namespace common\components;

use common\models\Blocks;
use common\models\Documents;
use common\models\Projects;
use common\modules\main\models\Category;

class Tools
{
    const DATA_TYPE_INT = 'int';
    const DATA_TYPE_FLOAT = 'float';
    const DATA_TYPE_BOOLEAN = 'boolean';
    const DATA_TYPE_STRING = 'string';
    const DATA_TYPE_ARRAY = 'array';

    public int $index = 0;
    public array $response = [
        'error' => false,
        'message' => null,
        'alert' => null,
        'data' => [],
    ];

    public function checkData($model, $key, $type): bool
    {
        switch ($type) {
            case self::DATA_TYPE_INT:
                if (!isset($model->$key) || $model->$key == null || $model->$key == 0) {
                    return false;
                }
                break;
            case self::DATA_TYPE_FLOAT:
                if (!isset($model->$key) || $model->$key == null || (int)$model->$key == 0) {
                    return false;
                }
                break;
            case self::DATA_TYPE_STRING:
                if (!isset($model->$key) || $model->$key == null) {
                    return false;
                }
                break;
            case self::DATA_TYPE_ARRAY:
                if (!isset($model->$key) || $model->$key == null || count($model->$key) == 0) {
                    return false;
                }
                break;
        }
        return true;
    }

    public static function generateBlogsNav()
    {
        $url = \Yii::$app->urlManager;
        $category = Category::find()->where(['parent_id' => Category::PARENT_ID_ROOT, 'belong' => Category::BELONG_BLOG])
            ->with('children')->asArray()->all();

        foreach ($category as $item) {
            if (!$item['children']) {
                echo '<li><a href="'. $url->createUrl(['/site/blogs', 'category' => $item['id']]) .'">'. $item['title'] .'</a></li>';
            }else {
                echo '<li class="dropdown"><a href="'. $url->createUrl(['/site/blogs', 'category' => $item['id']]) .'"><span>'. $item['title'] .'</span> <i class="bi bi-chevron-left"></i></a>';
                echo '<ul>';
                foreach ($item['children'] as $child) {
                    echo '<li><a href="'. $url->createUrl(['/site/blogs', 'category' => $child['id']]) .'">'. $child['title'] .'</a></li>';
                }
                echo '</ul>';
                echo '</li>';
            }
        }
    }

    public static function blogBreadcrumb($id, $parent, $belong = 'blogs')
    {
        if ($id != 0) {
            $category = Category::findOne(['id' => $parent]);
            if ($category) {
                self::blogBreadcrumb($id, $category->parent_id);
                if ($id == $category->id) {
                    if ($belong != 'blogs') {
                        echo '<li><a href="'. \Yii::$app->urlManager->createUrl(['/site/blogs', 'category' => $category->id]) .'">'. $category->title .'</a></li>';
                    }else {
                        echo '<li><a aria-current="page">'. $category->title .'</a></li>';
                    }
                }else {
                    echo '<li><a href="'. \Yii::$app->urlManager->createUrl(['/site/blogs', 'category' => $category->id]) .'">'. $category->title .'</a></li>';
                }
            }
        }
    }
}