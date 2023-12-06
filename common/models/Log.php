<?php

namespace common\models;

use common\components\Jdf;
use Yii;

/**
 * This is the model class for table "log".
 *
 * @property int $id
 * @property string $page_type
 * @property int $page_id
 * @property int $user_id
 * @property string $remote_addr
 * @property string $user_agent
 * @property int $date
 */
class Log extends \yii\db\ActiveRecord
{
    const PAGE_TYPE_LIVE = 'live';


    // filters
    public $name;
    public $lastname;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['page_type', 'page_id', 'user_id', 'remote_addr', 'user_agent', 'date'], 'required'],
            [['page_id', 'user_id', 'date'], 'integer'],
            [['page_type', 'remote_addr', 'user_agent'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'page_type' => Yii::t('app', 'Page Type'),
            'page_id' => Yii::t('app', 'Page ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'remote_addr' => Yii::t('app', 'Remote Addr'),
            'user_agent' => Yii::t('app', 'User Agent'),
            'date' => Yii::t('app', 'Date'),

            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
        ];
    }

    public static function inset($pageType, $pageId, $userId)
    {
        $model = new Log();

        $model->page_type = $pageType;
        $model->page_id = $pageId;
        $model->user_id = $userId;
        $model->remote_addr = $_SERVER['REMOTE_ADDR'];
        $model->user_agent = $_SERVER['HTTP_USER_AGENT'];
        $model->date = Jdf::jmktime();

        $model->save(false);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}
