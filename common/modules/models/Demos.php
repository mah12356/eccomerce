<?php

namespace common\modules\models;

use Yii;

/**
 * This is the model class for table "demos".
 *
 * @property int $id
 * @property string $description
 * @property string $video
 * @property string $for
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
            [['for'], 'required'],
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
            'description' => Yii::t('app', 'توضیحات'),
            'video' => Yii::t('app', 'Video'),
            'for' => Yii::t('app', 'برای'),
            'file' => Yii::t('app', 'ویدیو'),
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
