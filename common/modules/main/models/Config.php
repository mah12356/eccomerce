<?php

namespace common\modules\main\models;

use Yii;

/**
 * This is the model class for table "config".
 *
 * @property int $id
 * @property string $key
 * @property string $content
 * @property string $description
 */
class Config extends \yii\db\ActiveRecord
{
    const KEY_SITE_TITLE = 'site-title';
    const KEY_SITE_NAME = 'site-name';
    const KEY_SITE_RIGHTS = 'site-right';
    const KEY_SLOGAN = 'slogan';
    const KEY_LOGO = 'logo';
    const KEY_FAVICON = 'favicon';
    const KEY_EMAIL = 'email';
    const KEY_PHONE = 'phone';
    const KEY_MOBILE = 'mobile';
    const KEY_ADDRESS = 'address';
    const KEY_INSTAGRAM = 'instagram';
    const KEY_FACEBOOK = 'facebook';
    const KEY_TELEGRAM = 'telegram';
    const KEY_TWITTER = 'twitter';
    const KEY_WHATSAPP = 'whatsapp';
    const KEY_LINKEDIN = 'linkedin';
    const KEY_SMS_KEY = 'sms-key';
    const KEY_ZARINPAL = 'zarinpal';
    const KEY_DESCRIPTION = 'site-description';
    const KEY_DIET_TICKET = 'diet-ticket';
    const KEY_ANALYZE_TICKET = 'analyze-ticket';
    const KEY_ANALYZE_FORM = 'analyze-form';

    const UPLOAD_FILE = 'config';

    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['key'], 'required'],
            [['key', 'content'], 'string', 'max' => 255],
            [['description'], 'string'],
            [['key'], 'unique'],
            [['file'], 'file', 'extensions' => 'png'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'key' => Yii::t('app', 'کلیدواژه'),
            'content' => Yii::t('app', 'محتوا'),
            'description' => Yii::t('app', 'توضیحات'),
            'file' => Yii::t('app', 'بارگذاری فایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ConfigQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ConfigQuery(get_called_class());
    }

    public static function getKeyContent($key): ?string
    {
        $config = Config::findOne(['key' => $key]);
        if ($config){
            return $config->content;
        }else{
            return null;
        }
    }
}
