<?php

namespace common\models;

use common\components\Gadget;
use Yii;

/**
 * This is the model class for table "analyze".
 *
 * @property int $id
 * @property string $number
 * @property string $massage
 * @property string $status

 */
class Advertise extends \yii\db\ActiveRecord
{
    public $file;
    public $package_id;
  const STATUS_SENT = 'sent';
  const STATUS_NOT_SEND = 'not send';

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'advertise';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['number','massage','status'], 'required'],
            [['number','massage', 'status'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'number'=>"number",
            'massage'=>'massage',
            'status'=>'status'
        ];
    }

    /**
     * {@inheritdoc}
     * @return AdvertiseQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AdvertiseQuery(get_called_class());
    }
}