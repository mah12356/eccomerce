<?php

namespace common\models;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use Yii;

/**
 * This is the model class for table "answers".
 *
 * @property int $id
 * @property int $user_id
 * @property int $diet_id
 * @property int $question_id
 * @property int $option_id
 * @property string|null $response
 * @property int $date
 */
class Answers extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'answers';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'diet_id', 'question_id', 'date'], 'required'],
            [['user_id', 'diet_id', 'question_id', 'option_id', 'date'], 'integer'],
            [['response'], 'string', 'max' => 255],
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
            'diet_id' => Yii::t('app', 'Diet ID'),
            'question_id' => Yii::t('app', 'Question ID'),
            'option_id' => Yii::t('app', 'Option ID'),
            'response' => Yii::t('app', 'Response'),
            'date' => Yii::t('app', 'Date'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return AnswersQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new AnswersQuery(get_called_class());
    }

    public static function setResponse($data, $user_id, $diet_id)
    {
        $result = new Tools();

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($data as $key => $item) {
                $explode = explode('_', $key);
                if (isset($explode[1])) {
                    $question = Questions::findOne(['id' => $explode[1]]);
                    if ($question) {
                        $model = new Answers();

                        $model->user_id = $user_id;
                        $model->diet_id = $diet_id;
                        $model->question_id = $question->id;
                        $model->date = Jdf::jmktime();
                        if ($question->type == Questions::TYPE_NUMBER || $question->type == Questions::TYPE_TEXT) {
                            $option = false;
                            $model->response = $item;
                        }else {
                            $option = true;
                        }

                        if (!$model->save()) {
                            $transaction->rollBack();
                            $result->response['error'] = true;
                            $result->response['message'][$result->index] = Message::FAILED_TO_EXECUTE;
                            $result->response['alert'][$result->index] = '';
                            $result->index++;
                            return $result->response;
                        }

                        if ($option) {
//                            Gadget::preview($data);
                            foreach ($item as $value) {
                                $answerOptions = new AnswerOptions();

                                $answerOptions->answer_id = $model->id;
                                $answerOptions->option_id = $value;

                                if (!$answerOptions->save()) {
                                    $transaction->rollBack();
                                    $result->response['error'] = true;
                                    $result->response['message'][$result->index] = 'گزینه های پاسخ ذخیره نشد';
                                    $result->response['alert'][$result->index] = '';
                                    $result->index++;
                                    return $result->response;
                                }
                            }
                        }

                        $diet = Diet::findOne(['id' => $diet_id]);

                        if ($diet) {
                            $diet->status = Diet::STATUS_WAIT_FOR_RESPONSE;
                            if (!$diet->save()) {
                                $transaction->rollBack();
                                $result->response['error'] = true;
                                $result->response['message'][$result->index] = 'برنامه غذایی ویرایش نشد';
                                $result->response['alert'][$result->index] = '';
                                $result->index++;
                                return $result->response;
                            }
                        }
                    }
                }
            }
        }catch (\Exception $exception) {
            $transaction->rollBack();
            $result->response['error'] = true;
            $result->response['message'][$result->index] = Message::UNKNOWN_ERROR;
            $result->response['alert'][$result->index] = Alerts::UNKNOWN_ERROR;
            $result->index++;
            return $result->response;
        }

        $transaction->commit();
        return $result->response;
    }

    public function getOptions()
    {
        return $this->hasMany(AnswerOptions::className(), ['answer_id' => 'id']);
    }

    public function getQuestion()
    {
        return $this->hasOne(Questions::className(), ['id' => 'question_id']);
    }
}
