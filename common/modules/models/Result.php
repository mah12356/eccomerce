<?php

namespace common\modules\models;

use Yii;

/**
 * This is the model class for table "result".
 *
 * @property int $id
 * @property string $skill
 */
class Result extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'result';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['skill'], 'required'],
            [['skill'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'آیدی'),
            'skill' => Yii::t('app', 'نتایج'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ResultQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ResultQuery(get_called_class());
    }
}
