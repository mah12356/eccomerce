<?php

namespace common\modules\blog\models;

use common\modules\main\models\Category;
use Yii;

/**
 * This is the model class for table "tags".
 *
 * @property int $id
 * @property int $tag_id
 * @property int $article_id
 */
class Tags extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tags';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['tag_id', 'article_id'], 'required'],
            [['tag_id', 'article_id'], 'integer'],
            ['tag_id', 'validateTag'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'tag_id' => Yii::t('app', 'برچسب'),
            'article_id' => Yii::t('app', 'Article ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TagsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TagsQuery(get_called_class());
    }

    public function validateTag($attribute, $params)
    {
        if (!$this->hasErrors()) {
            if (Tags::findOne(['article_id' => $this->article_id, 'tag_id' => $this->tag_id])) {
                $this->addError($attribute, 'برچسب انتخاب شده تکراری می باشد');
            }
        }
    }

    public function getTag()
    {
        return $this->hasOne(Category::className(), ['id' => 'tag_id'])->andWhere(['belong' => Category::BELONG_TAG]);
    }
}
