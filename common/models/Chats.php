<?php

namespace common\models;

use common\components\Gadget;
use common\components\Jdf;
use Yii;
use yii\db\Exception;

/**
 * This is the model class for table "chats".
 *
 * @property int $id
 * @property int $parent_id
 * @property int $reference_id
 * @property string $belong
 * @property string $sender
 * @property string|null $text
 * @property string $image
 * @property string $audio
 * @property string $video
 * @property string $document
 * @property int $date
 */
class Chats extends \yii\db\ActiveRecord
{
    const BELONG_TICKET = 'ticket';
    const BELONG_CHANNEL = 'channel';

    const SENDER_ADMIN = 'admin';
    const SENDER_CLIENT = 'client';

    const UPLOAD_PATH = 'chats';

    public $imageFile;
    public $audioFile;
    public $videoFile;
    public $documentFile;

    public $referenceCount;
    public $referenceTo;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'chats';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['parent_id', 'belong', 'sender', 'date'], 'required'],
            [['parent_id', 'reference_id', 'date'], 'integer'],
            [['text'], 'string'],
            [['belong', 'sender', 'image', 'audio', 'video', 'document'], 'string', 'max' => 255],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg, webp, gif'],
            [['audioFile'], 'file', 'extensions' => 'mp3'],
            [['videoFile'], 'file', 'extensions' => 'mp4'],
            [['documentFile'], 'file', 'extensions' => 'pdf'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'parent_id' => Yii::t('app', 'Parent ID'),
            'belong' => Yii::t('app', 'Belong'),
            'sender' => Yii::t('app', 'Sender'),
            'text' => Yii::t('app', 'متن پیام'),
            'imageFile' => Yii::t('app', 'بارگذاری عکس'),
            'audioFile' => Yii::t('app', 'بارگذاری صوت'),
            'videoFile' => Yii::t('app', 'بارگذاری ویدیو'),
            'documentFile' => Yii::t('app', 'بارگذاری فایل'),
            'date' => Yii::t('app', 'Date'),
            'referenceCount' => Yii::t('app', 'تعداد پیام های کاربر'),
            'referenceTo' => Yii::t('app', 'ارجاع به بخش'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return ChatsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ChatsQuery(get_called_class());
    }

    /**
     * @return bool
     * @throws Exception
     */
    public function saveMessage(): bool
    {
        $transaction = Yii::$app->db->beginTransaction();
        $model = Tickets::findOne(['id' => $this->parent_id]);
        if (!$model) {
            return false;
        }

        $chat = Chats::find()->where(['parent_id' => $this->parent_id, 'sender' => Chats::SENDER_CLIENT])
            ->orderBy(['id' => SORT_DESC])->asArray()->one();

        if ($chat && $chat['reference_id'] != 0) {
            $responseTicket = Tickets::findOne(['id' => $chat['reference_id']]);

            if ($responseTicket) {
                $responseChat = new Chats();

                $responseChat->parent_id = $chat['reference_id'];
                $responseChat->belong = Chats::BELONG_TICKET;
                $responseChat->sender = $this->sender;
                $responseChat->text = $this->text;

                if ($this->image) {
                    $responseChat->image = $this->image;
                }
                if ($this->audio) {
                    $responseChat->audio = $this->audio;
                }
                if ($this->video) {
                    $responseChat->video = $this->video;
                }
                if ($this->document) {
                    $responseChat->document = $this->document;
                }
                $responseChat->date = Jdf::jmktime();

                if ($responseChat->sender == self::SENDER_CLIENT) {
                    $responseTicket->status = Tickets::STATUS_NEW_MESSAGE_ADMIN;
                }else {
                    $responseTicket->status = Tickets::STATUS_NEW_MESSAGE_CLIENT;
                }

                $responseChat->date = Jdf::jmktime();
                $responseTicket->date_update = Jdf::jmktime();

                if (!$responseChat->save() || !$responseTicket->save()) {
                    $transaction->rollBack();
                    return false;
                }
            }
        }

        if ($this->sender == self::SENDER_CLIENT) {
            $model->status = Tickets::STATUS_NEW_MESSAGE_ADMIN;
        }else {
            $model->status = Tickets::STATUS_NEW_MESSAGE_CLIENT;
        }

        $this->date = Jdf::jmktime();
        $model->date_update = Jdf::jmktime();

        if ($this->save() && $model->save()) {
            $transaction->commit();
            return true;
        }else {
            $transaction->rollBack();
            return false;
        }
    }
}
