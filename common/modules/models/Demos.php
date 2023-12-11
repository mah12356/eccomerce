<?php

namespace common\modules\models;

use Yii;

/**
 * This is the model class for table "demos".
 *
 * @property int $id
 * @property string $video
 * @property string $description
 * @property string|null $for
 */
class Demos extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'demos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['for'],'required'],
            [['description'], 'string'],
            [['for'], 'string', 'max' => 300],
            [['file'],'file']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'آیدی'),
            'file' => Yii::t('app', 'ویدیو'),
            'description' => Yii::t('app', 'توضیحات'),
            'for' => Yii::t('app', 'برای'),
            'video' => Yii::t('app', 'ویدیو'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return DemosQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new DemosQuery(get_called_class());
    }
}
