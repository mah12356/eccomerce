<?php

namespace common\modules\models;

use Yii;

/**
 * This is the model class for table "package_design".
 *
 * @property int $id
 * @property string $description
 */
class PackageDesign extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'package_design';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['description'], 'required'],
            [['description'], 'string', 'max' => 300],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'آیدی'),
            'description' => Yii::t('app', 'دلیل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return PackageDesignQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PackageDesignQuery(get_called_class());
    }
}
