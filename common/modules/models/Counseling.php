<?php

namespace common\modules\models;

use Yii;

/**
 * This is the model class for table "counseling".
 *
 * @property int $id
 * @property string $name
 * @property int $phone
 */
class Counseling extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'counseling';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'phone'], 'required'],
            [['phone'], 'string'],
            [['name'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'name' => Yii::t('app', 'Name'),
            'phone' => Yii::t('app', 'Phone'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return CounselingQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new CounselingQuery(get_called_class());
    }
}
