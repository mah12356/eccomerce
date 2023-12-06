<?php

namespace common\models;

use common\components\Gadget;
use common\components\Jdf;
use common\components\Tools;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "tickets".
 *
 * @property int $id
 * @property int $user_id
 * @property string $type
 * @property string $title
 * @property string $status
 * @property int $date
 * @property int $date_update
 * @property string $name
 * @property string $lastname
 * @property string $mobile
 */
class Tickets extends ActiveRecord
{
    const TYPE_DIET = 'diet';
    const TYPE_SUPPORT = 'support';
    const TYPE_COACH = 'coach';
    const TYPE_DEVELOPER = 'dev';
    const TYPE_ANALYZE = 'analyze';

    const STATUS_NEW_MESSAGE_ADMIN = 'admin message';
    const STATUS_NEW_MESSAGE_CLIENT = 'client message';
    const STATUS_CHECKED_ALL_ADMIN = 'admin checked';
    const STATUS_CHECKED_ALL_CLIENT = 'client checked';

    public $name;
    public $lastname;
    public $mobile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'tickets';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'title'], 'required'],
            [['user_id', 'date', 'date_update'], 'integer'],
            [['type', 'title', 'status'], 'string', 'max' => 255],
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
            'type' => Yii::t('app', 'Type'),
            'title' => Yii::t('app', 'Title'),
            'status' => Yii::t('app', 'Status'),
            'name' => Yii::t('app', 'نام'),
            'lastname' => Yii::t('app', 'نام خانوادگی'),
            'mobile' => Yii::t('app', 'شماره موبایل'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return TicketsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TicketsQuery(get_called_class());
    }

    public static function getType()
    {
        return [
            self::TYPE_DIET => 'مشاور تغذیه',
            self::TYPE_ANALYZE => 'آنالیز بدنی',
            self::TYPE_COACH => 'مهساآنلاین',
            self::TYPE_DEVELOPER => 'تیم فنی',
        ];
    }

    public static function defineTickets($user_id)
    {
        if (!Tickets::findOne(['user_id' => $user_id, 'type' => self::TYPE_DIET])) {
            $model = new Tickets();

            $model->user_id = $user_id;
            $model->type = self::TYPE_DIET;
            $model->title = 'تماس با مشاور تغذیه';
            $model->date = Jdf::jmktime();
            $model->date_update = Jdf::jmktime();

            $model->save(false);
        }

        if (!Tickets::findOne(['user_id' => $user_id, 'type' => self::TYPE_SUPPORT])) {
            $model = new Tickets();

            $model->user_id = $user_id;
            $model->type = self::TYPE_SUPPORT;
            $model->title = 'تماس با پشتیبانی';
            $model->date = Jdf::jmktime();
            $model->date_update = Jdf::jmktime();

            $model->save(false);
        }

        if (!Tickets::findOne(['user_id' => $user_id, 'type' => self::TYPE_COACH])) {
            $model = new Tickets();

            $model->user_id = $user_id;
            $model->type = self::TYPE_COACH;
            $model->title = 'تماس با مهساآنلاین';
            $model->date = Jdf::jmktime();
            $model->date_update = Jdf::jmktime();

            $model->save(false);
        }

        if (!Tickets::findOne(['user_id' => $user_id, 'type' => self::TYPE_DEVELOPER])) {
            $model = new Tickets();

            $model->user_id = $user_id;
            $model->type = self::TYPE_DEVELOPER;
            $model->title = 'تماس با تیم فنی';
            $model->date = Jdf::jmktime();
            $model->date_update = Jdf::jmktime();

            $model->save(false);
        }

        if (!Tickets::findOne(['user_id' => $user_id, 'type' => self::TYPE_ANALYZE])) {
            $model = new Tickets();

            $model->user_id = $user_id;
            $model->type = self::TYPE_ANALYZE;
            $model->title = 'آنالیز بدنی';
            $model->date = Jdf::jmktime();
            $model->date_update = Jdf::jmktime();

            $model->save(false);
        }
    }

    public static function statusManager($id, $seen)
    {
        $model = Tickets::findOne(['id' => $id]);
        if ($model) {
            if ($seen == 'client' && $model->status == self::STATUS_NEW_MESSAGE_CLIENT) {
                $model->status = self::STATUS_CHECKED_ALL_CLIENT;
            }
            if ($seen == 'admin' && $model->status == self::STATUS_NEW_MESSAGE_ADMIN) {
                $model->status = self::STATUS_CHECKED_ALL_ADMIN;
            }

            $model->save(false);
        }
    }

    public static function referenceMessage($id, $type, $count)
    {
        $result = new Tools();

        $model = Tickets::findOne(['id' => $id]);
        if (!$model) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'تیکت مورد نظر یافت نشد';
            $result->response['alert'][$result->index] = 'ticket not found';
            $result->index++;
            return $result->response;
        }

        if ($model->type == $type) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'امکان ارجاع به بخش مشابه وجود ندارد';
            $result->response['alert'][$result->index] = 'reference to same section';
            $result->index++;
            return $result->response;
        }

        $referenceTo = Tickets::findOne(['user_id' => $model->user_id, 'type' => $type]);
        if (!$referenceTo) {
            $referenceTo = new Tickets();

            $referenceTo->user_id = $model->user_id;
            $referenceTo->type = $type;
            $referenceTo->date = Jdf::jmktime();
            $referenceTo->date_update = Jdf::jmktime();

            switch ($type) {
                case self::TYPE_DIET:
                    $referenceTo->title = 'تماس با مشاور تغذیه';
                    break;
                case self::TYPE_SUPPORT:
                    $referenceTo->title = 'تماس با پشتیبانی';
                    break;
                case self::TYPE_COACH:
                    $referenceTo->title = 'تماس با مهساآنلاین';
                    break;
                case self::TYPE_DEVELOPER:
                    $referenceTo->title = 'تماس با تیم فنی';
                    break;
                default:
                    $referenceTo->title = 'n/a';
                    break;
            }

            $referenceTo->save(false);
        }

        $msg = Chats::find()->where(['parent_id' => $id, 'belong' => Chats::BELONG_TICKET, 'sender' => Chats::SENDER_CLIENT])
            ->orderBy(['id' => SORT_DESC])->limit($count)->asArray()->all();

        if (!$msg) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = 'پیامی برای ارجاع وجود ندارد';
            $result->response['alert'][$result->index] = 'no chat';
            $result->index++;
            return $result->response;
        }

        foreach (array_reverse($msg) as $item) {
            $chats = new Chats();

            $chats->parent_id = $referenceTo->id;
            $chats->reference_id = $id;
            $chats->belong = Chats::BELONG_TICKET;
            $chats->sender = Chats::SENDER_CLIENT;
            $chats->text = $item['text'];
            $chats->image = $item['image'];
            $chats->audio = $item['audio'];
            $chats->video = $item['video'];
            $chats->document = $item['document'];
            $chats->date = $item['date'];

//            $chats->validate();
//            Gadget::preview($chats->errors);

            $chats->saveMessage();
        }

        $referenceTo->status = Tickets::STATUS_NEW_MESSAGE_ADMIN;
        $referenceTo->save();

        $chats = new Chats();
        $chats->parent_id = $id;
        $chats->belong = Chats::BELONG_TICKET;
        $chats->sender = Chats::SENDER_ADMIN;
        $chats->text = 'پیام شما به بخش مربوطه ارجاع یافت';
        $chats->date = Jdf::jmktime();

        $chats->saveMessage();

        $model->status = self::STATUS_NEW_MESSAGE_ADMIN;
        $model->save();

        return $result->response;
    }

    public function getChats()
    {
        return $this->hasMany(Chats::className(), ['parent_id' => 'id'])->andWhere(['belong' => Chats::BELONG_TICKET]);
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->select(['id', 'name', 'lastname', 'mobile']);
    }
}
