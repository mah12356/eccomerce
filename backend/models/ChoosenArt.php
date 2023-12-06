<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "choosen_art".
 *
 * @property int $id
 * @property int $art_id
 * @property string|null $banner
 * @property string $title
 * @property int $date
 * @property string $status
 */
class ChoosenArt extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'choosen_art';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['art_id', 'title', 'date'], 'required'],
            [['art_id', 'date'], 'integer'],
            [['banner', 'title', 'status'], 'string', 'max' => 300],
            [['category_id'],'integer'],
            [['art_id'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'art_id' => Yii::t('app', 'Art ID'),
            'banner' => Yii::t('app', 'Banner'),
            'title' => Yii::t('app', 'Title'),
            'date' => Yii::t('app', 'Date'),
            'status' => Yii::t('app', 'Status'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChoosenArtQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChoosenArtQuery(get_called_class());
    }
}
