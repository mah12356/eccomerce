<?php

namespace common\models;

use common\components\Tools;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "section_contents".
 *
 * @property int $id
 * @property int $section_id
 * @property string $title
 * @property string $file
 */
class SectionContents extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'section_contents';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['section_id', 'title', 'file'], 'required'],
            [['section_id'], 'integer'],
            [['title', 'file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'section_id' => Yii::t('app', 'Section ID'),
            'title' => Yii::t('app', 'Title'),
            'file' => Yii::t('app', 'File'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SectionContentsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SectionContentsQuery(get_called_class());
    }

    public static function defineRecord($section_id, $files): array
    {
        $i = 0;
        $title = 'پارت-';
        $transaction = Yii::$app->db->beginTransaction();

        $result = new Tools();

        foreach ($files as $item) {
            $i++;
            $model = new SectionContents();
            $model->section_id = $section_id;
            $model->title = $title . $i;
            $model->file = $item;
            if (!$model->save()) {
                $transaction->rollBack();
                $result->response['error'] = true;
                $result->response['message'] = 'خطا در ذخیره سازی فایل های جلسه';
                $result->response['alert'] = 'failed to save section videos';
                $result->index++;
                return $result->response;
            }
        }
        $transaction->commit();
        return $result->response;
    }
}
