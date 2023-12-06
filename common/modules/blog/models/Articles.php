<?php

namespace common\modules\blog\models;

use common\modules\main\models\Category;
use Yii;

/**
 * This is the model class for table "articles".
 *
 * @property int $id
 * @property int $category_id
 * @property string $page_title
 * @property string $title
 * @property string $introduction
 * @property int $modify_date
 * @property string $poster
 * @property string $banner
 * @property string $video
 * @property string|null $alt
 * @property int $publish
 * @property int $preview
 */
class Articles extends \yii\db\ActiveRecord
{
    const PUBLISH_TRUE = 1;
    const PUBLISH_FALSE = 0;

    const PREVIEW_ON = 1;
    const PREVIEW_OFF = 0;

    const UPLOAD_PATH = 'article';

    public $imageFile;
    public $posterFile;
    public $videoFile;

    public $time;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'articles';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['category_id', 'title', 'introduction', 'modify_date'], 'required'],
            [['category_id', 'modify_date', 'preview'], 'integer'],
            [['page_title', 'title', 'banner', 'alt'], 'string', 'max' => 255],
            [['imageFile', 'posterFile'], 'file', 'extensions' => 'png, jpg, jpeg'],
            [['videoFile'], 'file', 'extensions' => 'mp4'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'شناسه'),
            'category_id' => Yii::t('app', 'دسته مقاله'),
            'page_title' => Yii::t('app', 'عنوان صفحه'),
            'title' => Yii::t('app', 'عنوان'),
            'introduction' => Yii::t('app', 'توضیح اجمالی'),
            'modify_date' => Yii::t('app', 'تاریخ نشر'),
            'banner' => Yii::t('app', 'Banner'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
            'publish' => Yii::t('app', 'وضعیت انتشار'),
            'preview' => Yii::t('app', 'Preview'),
            'posterFile' => Yii::t('app', 'بارگذاری پستر'),
            'imageFile' => Yii::t('app', 'بارگذاری بنر'),
            'videoFile' => Yii::t('app', 'بارگذاری ویدیو'),
            'time' => Yii::t('app', 'ساعت نشر'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ArticlesQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ArticlesQuery(get_called_class());
    }

    public static function getCategory()
    {
        $category = array();
        $blogsCat = Category::findAll(['parent_id' => Category::PARENT_ID_ROOT, 'belong' => Category::BELONG_BLOG]);
        foreach ($blogsCat as $item) {
            $category[$item->id] = $item->title;
        }
        return $category;
    }

    public function getCat()
    {
        return $this->hasOne(Category::className(), ['id' => 'category_id'])->andWhere(['belong' => Category::BELONG_BLOG]);
    }

    public function getTags()
    {
        return $this->hasMany(Tags::className(), ['article_id' => 'id']);
    }

    public function getPosts()
    {
        return $this->hasMany(Posts::className(), ['page_id' => 'id'])->orderBy(['order' => SORT_ASC]);
    }
}
