<?php

namespace common\models;

use common\modules\blog\models\Articles;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "community".
 *
 * @property int $id
 * @property int $user_id
 * @property int $parent_id
 * @property string $belong
 * @property string $text
 * @property string|null $reply
 * @property int $date
 * @property string $status
 */
class Community extends ActiveRecord
{
    const BELONG_BLOGS = 'blog';
    const BELONG_PRODUCTS = 'product';
    const BELONG_LIVE = 'live';

    const STATUS_PENDING = 'pending';
    const STATUS_SUBMIT = 'submit';
    const STATUS_DENY = 'deny';

    public $captcha;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'community';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'parent_id', 'belong', 'text', 'date'], 'required'],
            [['user_id', 'parent_id', 'date'], 'integer'],
            [['text'], 'string'],
            [['belong', 'reply', 'status'], 'string', 'max' => 255],
            ['captcha', 'captcha', 'message' => 'کد امنیتی را به درستی وارد کنید'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'user_id' => Yii::t('app', 'User ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'belong' => Yii::t('app', 'Belong'),
            'text' => Yii::t('app', 'متن'),
            'reply' => Yii::t('app', 'پاسخ ادمین'),
            'date' => Yii::t('app', 'تاریخ ثبت'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CommunityQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CommunityQuery(get_called_class());
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    public function getArticle()
    {
        return $this->hasOne(Articles::className(), ['id' => 'parent_id']);
    }
}
