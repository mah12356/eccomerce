<?php

namespace backend\modules\coach\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use common\components\VideoStreams;
use common\models\Chats;
use common\models\Community;
use common\models\Sections;
use common\models\Tickets;
use common\models\TicketsSearch;
use common\models\User;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class TicketsController extends ActiveController
{
    public $modelClass = 'common\models\Tickets';
    public object $result;

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        $behaviors['corsFilter'] = [
            'class' => Cors::className(),
            'cors' => [
                'Origin' => ['*'],
                'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATH', 'DELETE', 'HEAD', 'OPTIONS'],
                'Access-Control-Request-Headers' => ['*'],
                'Access-Control-Allow-Credentials' => true,
            ],
        ];
        $behaviors['authenticator'] = [
            'class' => CompositeAuth::className(),
            'authMethods' => [
                HttpBearerAuth::className(),
            ],
            'except' => [],
        ];
        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => ['list', 'chat'],
            'rules' => [
                [
                    'actions' => ['list', 'chat'],
                    'allow' => true,
                    'roles' => [User::ROLE_COACH],
                ],
            ],
        ];
        return $behaviors;
    }

    public function beforeAction($action)
    {
        $this->result = new Tools();
        return parent::beforeAction($action);
    }

    // لیست تیکت ها ارسال شده ی هر گروه
    public function actionList()
    {
        $post = Gadget::setPost();

        if (!isset($post['type']) || !$post['type']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'نوع تیکت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** type **';
            $this->result->index++;
        }
        if (!isset($post['status'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'وضعیت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** status **';
            $this->result->index++;
        }
        if (!isset($post['mobile'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'وضعیت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** status **';
            $this->result->index++;
        }
        if (!isset($post['page'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'وضعیت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** status **';
            $this->result->index++;
        }
        if ($this->result->response['error']){
            return $this->result->response;
        }

        if (!in_array($post['type'], [Tickets::TYPE_DIET, Tickets::TYPE_ANALYZE, Tickets::TYPE_COACH])) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'نوع تیکت فاقد اعتبار است';
            $this->result->response['alert'] = 'parameter type is invalid';
            $this->result->index++;
            return $this->result->response;
        }

        $status = 'admin message';

        if ($post['status'] && $post['status'] != $status) {
            $status = ['admin checked', 'client message', 'client checked'];
        }

        $model = Tickets::find()->with('user');

        $model->joinWith('user');

        $model->andFilterWhere([
            'type' => $post['type'],
            'tickets.status' => $status,
        ]);

        if (isset($post['mobile']) && $post['mobile']) {
            $model->andFilterWhere(['like', 'user.mobile', $post['mobile']]);
        }

        $provider = new ActiveDataProvider([
            'query' => $model,
            'pagination' => [
                'pageSize' => 35,
            ],
        ]);

        if (!$post['page']) {
            $_GET['page'] = 1;
        }else {
            $_GET['page'] = $post['page'];
        }

        $this->result->response['data']['total'] = $provider->getTotalCount();
        $this->result->response['data']['ticket'] = Tickets::find()->select(['id', 'user_id', 'date_update'])
            ->where(['IN', 'id', ArrayHelper::map($provider->getModels(), 'id', 'id')])->with('user')
            ->asArray()->all();

        return $this->result->response;
    }

    // پیام های داخل یک تیکت
    public function actionChat()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه تیکت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** id **';
            $this->result->index++;
            return $this->result->response;
        }

        $ticket = Tickets::find()->where(['id' => $post['id']])->asArray()->one();
        if (!$ticket) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'تیکت مورد نظر یافت نشد';
            $this->result->response['alert'] = 'ticket not found';
            $this->result->index++;
            return $this->result->response;
        }

//        $this->result->response['data'] = Chats::find()->select(['id', 'sender', 'text', 'image', 'video', 'audio', 'document', 'date'])
//            ->where(['parent_id' => $post['id'], 'belong' => Chats::BELONG_TICKET])
//            ->orderBy(['id' => SORT_DESC])->asArray()->all();


        $this->result->response['data'] = Chats::find()->select(['id', 'sender', 'text', 'image', 'video', 'audio', 'document', 'date'])
            ->where(['parent_id' => $post['id'], 'belong' => Chats::BELONG_TICKET])
            ->orderBy(['id' => SORT_DESC])->limit(50)->asArray()->all();

        return $this->result->response;
    }

    // ارسال پیام‌
    public function actionSendMessage()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'شناسه تیکت' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** id **';
            $this->result->index++;
        }
        if (!isset($post['text'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'متن پیام' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** text **';
            $this->result->index++;
        }
        if (!isset($post['image'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'عکس' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** image **';
            $this->result->index++;
        }
        if (!isset($post['audio'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'فایل صوتی' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** audio **';
            $this->result->index++;
        }
        if (!isset($post['video'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'ویدیو' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** video **';
            $this->result->index++;
        }
        if (!isset($post['document'])) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'فایل' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** document **';
            $this->result->index++;
        }
        if ($this->result->response['error']) {
            return $this->result->response;
        }

        if (!$post['text'] && !$post['image'] && !$post['video'] && !$post['audio'] && !$post['document']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'پیام ارسالی نباید خالی باشد';
            $this->result->response['alert'][$this->result->index] = 'message is empty';
            $this->result->index++;
            return $this->result->response;
        }

        try {
            $model = new Chats();

            $model->parent_id = $post['id'];
            $model->belong = Chats::BELONG_TICKET;
            $model->sender = Chats::SENDER_ADMIN;

            if ($post['text']) {
                $model->text = $post['text'];
            }
            if ($post['image']) {
                $moveFile = Gadget::renameFile($post['image'], 'tmp', Chats::UPLOAD_PATH, $model->parent_id . '__IMAGE__' . Jdf::jmktime());
                if (!$moveFile['error']) {
                    $model->image = $moveFile['name'];
                }else {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = 'خطا در ثبت عکس';
                    $this->result->response['alert'][$this->result->index] = 'move image failed';
                    $this->result->index++;
                }
            }
            if ($post['video']) {
                $moveFile = Gadget::renameFile($post['video'], 'tmp', Chats::UPLOAD_PATH, $model->parent_id . '__VIDEO__' . Jdf::jmktime());
                if (!$moveFile['error']) {
                    $model->video = $moveFile['name'];
                }else {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = 'خطا در ثبت ویدیو';
                    $this->result->response['alert'][$this->result->index] = 'move video failed';
                    $this->result->index++;
                }
            }
            if ($post['audio']) {
                $moveFile = Gadget::renameFile($post['audio'], 'tmp', Chats::UPLOAD_PATH, $model->parent_id . '__AUDIO__' . Jdf::jmktime());
                if (!$moveFile['error']) {
                    $model->audio = $moveFile['name'];
                }else {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = 'خطا در ثبت فایل صوتی';
                    $this->result->response['alert'][$this->result->index] = 'move audio failed';
                    $this->result->index++;
                }
            }
            if ($post['document']) {
                $moveFile = Gadget::renameFile($post['document'], 'tmp', Chats::UPLOAD_PATH, $model->parent_id . '__DOCUMENT__' . Jdf::jmktime());
                if (!$moveFile['error']) {
                    $model->document = $moveFile['name'];
                }else {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = 'خطا در ثبت فایل سند';
                    $this->result->response['alert'][$this->result->index] = 'move document failed';
                    $this->result->index++;
                }
            }
            if ($this->result->response['error']) {
                return $this->result->response;
            }

            if ($model->saveMessage()) {
                $this->result->response['message'] = 'پیام شما با موفقیت ارسال شد';
                $this->result->response['alert'] = 'message sent successfully';
            }else {
                $this->result->response['error'] = true;
                $this->result->response['message'][$this->result->index] = Message::FAILED_TO_EXECUTE;
                $this->result->response['alert'][$this->result->index] = Alerts::ERROR_MODEL_SAVE;
                $this->result->index++;
            }
        }catch (\Exception $exception) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = Message::UNKNOWN_ERROR;
            $this->result->response['alert'][$this->result->index] = Alerts::UNKNOWN_ERROR;
            $this->result->index++;
        }

        return $this->result->response;
    }
}