<?php

namespace common\modules\main\models;

use common\models\Course;
use common\models\Packages;
use common\modules\blog\models\Articles;
use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $belong
 * @property string $title
 * @property int $modify_date
 * @property string $banner
 * @property string|null $alt
 * @property int $preview
 */
class Category extends \yii\db\ActiveRecord
{
    const PARENT_ID_ROOT = 0;

    const BELONG_PRODUCT = 'product';
    const BELONG_JOB = 'job';
    const BELONG_MENU = 'menu';
    const BELONG_BLOG = 'blog';
    const BELONG_TAG = 'tag';
    const BELONG_COURSE = 'course';
    const BELONG_ARCHIVE = 'archive';

    const PREVIEW_ON = 1;
    const PREVIEW_OFF = 0;

    public $imageFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'belong', 'title', 'modify_date'], 'required'],
            [['parent_id', 'modify_date', 'preview'], 'integer'],
            [['belong', 'title', 'banner', 'alt'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'شناسه'),
            'parent_id' => Yii::t('app', 'شناسه والد'),
            'belong' => Yii::t('app', 'متعلق به'),
            'title' => Yii::t('app', 'عنوان'),
            'modify_date' => Yii::t('app', 'Modify Date'),
            'banner' => Yii::t('app', 'Banner'),
            'alt' => Yii::t('app', 'توضیحات عکس'),
            'preview' => Yii::t('app', 'Preview'),
            'imageFile' => Yii::t('app', 'بارگذاری عکس'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CategoryQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CategoryQuery(get_called_class());
    }

    public function getChildren()
    {
        return $this->hasMany(Category::className(), ['parent_id' => 'id']);
    }

    public function getPackages()
    {
        return $this->hasMany(Packages::className(), ['category' => 'id'])->where(['status' => Packages::STATUS_READY]);
    }

    public function getArticles()
    {
        return $this->hasMany(Articles::className(), ['category_id' => 'id'])->andWhere(['publish' => Articles::PUBLISH_TRUE])->orderBy(['modify_date'=>SORT_DESC]);
    }
}
