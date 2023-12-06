<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "groups".
 *
 * @property int $id
 * @property string $type
 * @property string $title
 * @property int $start_date
 * @property int $start_time
 * @property int $finish_time
 * @property int $sections_count
 * @property int $interval
 */
class Groups extends \yii\db\ActiveRecord
{
    const TYPE_LIVE = 'live'; // برگزاری به صورت لایو
    const TYPE_OFFLINE = 'offline'; // برگزاری به صورت ویدیو آماده

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'groups';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'title', 'start_date', 'start_time', 'finish_time', 'sections_count', 'interval'], 'required'],
            [['start_date', 'sections_count'], 'integer'],
            [['type', 'start_time', 'finish_time', 'title'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'نوع'),
            'title' => Yii::t('app', 'عنوان'),
            'start_date' => Yii::t('app', 'تاریخ شروع جلسات'),
            'start_time' => Yii::t('app', 'ساعت شروع'),
            'finish_time' => Yii::t('app', 'ساعت پایان'),
            'sections_count' => Yii::t('app', 'تعداد جلسات'),
            'interval' => Yii::t('app', 'روز های جلسه'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return GroupsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new GroupsQuery(get_called_class());
    }

    public static function getType(): array
    {
        return [
            self::TYPE_LIVE => 'برگزاری به صورت لایو',
            self::TYPE_OFFLINE => 'برگزاری به صورت ویدیو آماده',
        ];
    }
}
