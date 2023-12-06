<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "answer_options".
 *
 * @property int $id
 * @property int $answer_id
 * @property int $option_id
 */
class AnswerOptions extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answer_options';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['answer_id', 'option_id'], 'required'],
            [['answer_id', 'option_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'answer_id' => Yii::t('app', 'Answer ID'),
            'option_id' => Yii::t('app', 'Option ID'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AnswerOptionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AnswerOptionsQuery(get_called_class());
    }

    public function getContent()
    {
        return $this->hasMany(Options::className(), ['id' => 'option_id']);
    }
}
