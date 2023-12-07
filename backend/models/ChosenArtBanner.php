<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "chosen_art_banner".
 *
 * @property int $id
 * @property string $src
 * @property string|null $alt
 * @property string $for
 */
class ChosenArtBanner extends \yii\db\ActiveRecord
{
    public $file;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chosen_art_banner';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['src', 'for'], 'required'],
            [['src'], 'string', 'max' => 225],
            [['alt', 'for'], 'string', 'max' => 300],
            [['file'],'file']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ایدی'),
            'file' => Yii::t('app', 'عکس'),
            'alt' => Yii::t('app', 'Alt'),
            'for' => Yii::t('app', 'برای'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChosenArtBannerQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChosenArtBannerQuery(get_called_class());
    }
}
