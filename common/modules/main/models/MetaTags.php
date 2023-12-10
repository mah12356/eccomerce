<?php

namespace common\modules\main\models;

use common\models\MetaTag;
use Yii;

/**
 * This is the model class for table "meta_tags".
 *
 * @property int $id
 * @property int $parent_id
 * @property string $belong
 * @property string $type
 * @property string $content
 * @property string $value
 */
class MetaTags extends \yii\db\ActiveRecord
{
    const BELONG_HOME = 'home';
    const BELONG_PAGE = 'page';
    const BELONG_PRODUCT = 'product';
    const BELONG_BLOG = 'blog';
    const BELONG_ARCHIVE = 'archive';

    const TAG = 'tags';

    const CATEGORY = 'category';
    const TYPE_REL = 'rel';
    const TYPE_NAME = 'name';
    const TYPE_PROPERTY = 'property';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'meta_tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'belong', 'type', 'content', 'value'], 'required'],
            [['parent_id'], 'integer'],
            [['belong', 'type', 'content', 'value'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'belong' => Yii::t('app', 'Belong'),
            'type' => Yii::t('app', 'نوع'),
            'content' => Yii::t('app', 'عنوان'),
            'value' => Yii::t('app', 'محتوا'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return MetaTagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new MetaTagsQuery(get_called_class());
    }

    public static function getType()
    {
        return [
            self::TYPE_REL => self::TYPE_REL,
            self::TYPE_NAME => self::TYPE_NAME,
            self::TYPE_PROPERTY => self::TYPE_PROPERTY,
        ];
    }

    public static function setMetaTags($parent_id, $belong)
    {
        $metaTags = MetaTags::findAll(['parent_id' => $parent_id, 'belong' => $belong]);

        if ($metaTags){
            foreach ($metaTags as $item){
                switch ($item['type']) {
                    case 'name':
                        \Yii::$app->view->registerMetaTag([
                            'name' => $item->content,
                            'content' => $item->value,
                        ]);
                        break;
                    case 'property':
                        \Yii::$app->view->registerMetaTag([
                            'property' => $item->content,
                            'content' => $item->value,
                        ]);
                        break;
                    case 'rel':
                        if ($item->content == 'canonical') {
                            \Yii::$app->view->registerLinkTag([
                                'rel' => $item->content,
                                'href' => $item->value,
                            ]);
                        }else{
                            \Yii::$app->view->registerLinkTag([
                                'rel' => $item->content,
                                'content' => $item->value,
                            ]);
                        }
                        break;
                }
            }
        }
    }
}
