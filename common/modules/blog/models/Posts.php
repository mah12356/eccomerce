<?php

namespace common\modules\blog\models;

use common\components\Gadget;
use Yii;

/**
 * This is the model class for table "posts".
 *
 * @property int $id
 * @property int $page_id
 * @property string $belong
 * @property int $order
 * @property string|null $title
 * @property string $text
 * @property string|null $tip
 * @property string|null $btn_text
 * @property string|null $btn_link
 * @property string $banner
 * @property string|null $alt
 */
class Posts extends \yii\db\ActiveRecord
{
    const BELONG_ARTICLES = 'articles';
    const BELONG_CATEGORIES = 'categories';
    const BELONG_TAGS = 'tags';
    const BELONG_HOME = 'home_page';
    const BELONG_ABOUT = 'about_us';

    const UPLOAD_PATH = 'post';

    const UPLOAD_IMG = 'img';

    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'posts';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_id', 'belong', 'order', 'text'], 'required'],
            [['page_id', 'order'], 'integer'],
            [['title', 'text', 'tip'], 'string'],
            [['btn_text', 'btn_link', 'alt'], 'string', 'max' => 255],
            [['uploadFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp, gif, mp4'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_id' => Yii::t('app', 'شناسه صفحه'),
            'title' => Yii::t('app', 'عنوان'),
            'text' => Yii::t('app', 'متن'),
            'tip' => Yii::t('app', 'تیپ'),
            'btn_text' => Yii::t('app', 'نوشته دکمه'),
            'btn_link' => Yii::t('app', 'لینک دکمه'),
            'order' => Yii::t('app', 'ترتیب نمایش'),
            'uploadFile' => Yii::t('app', 'بارگذاری بنر'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PostsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PostsQuery(get_called_class());
    }

//    public static function linkToBlog($page_id): array
//    {
//        $related[0] = 'ندارد';
//        $model = Articles::find()->where(['!=', 'id', $page_id])->all();
//
//        foreach ($model as $item) {
//            $related[$item->id] = $item->title;
//        }
//
//        return $related;
//    }
//
//    public function getSimilar()
//    {
//        return $this->hasOne(Articles::className(), ['id' => 'related']);
//    }

    public function getImages()
    {
        return $this->hasMany(Gallery::className(), ['parent_id' => 'id'])->andWhere(['belong' => Gallery::BELONG_POSTS]);
    }
}
