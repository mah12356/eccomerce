<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "channels".
 *
 * @property int $id
 * @property int $coach_id
 * @property int $package_id
 * @property string $name
 * @property string $avatar
 */
class Channels extends \yii\db\ActiveRecord
{
    const UPLOAD_PATH = 'channels';

    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'channels';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['coach_id', 'package_id', 'name'], 'required'],
            [['coach_id', 'package_id'], 'integer'],
            [['name', 'avatar'], 'string', 'max' => 255],
            [['file'], 'file', 'extensions' => 'png, jpg, jpeg'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'package_id' => Yii::t('app', 'پکیج'),
            'name' => Yii::t('app', 'عنوان کانال'),
            'avatar' => Yii::t('app', 'عکس کانال'),
            'file' => Yii::t('app', 'بارگذاری فایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChannelsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChannelsQuery(get_called_class());
    }

    public function getChats()
    {
        return $this->hasMany(Chats::className(), ['parent_id' => 'id'])->andWhere(['belong' => Chats::BELONG_CHANNEL]);
    }
}
