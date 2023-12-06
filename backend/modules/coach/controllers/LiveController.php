<?php

namespace backend\modules\coach\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Message;
use common\components\Tools;
use common\components\VideoStreams;
use common\models\Community;
use common\models\Sections;
use common\models\User;
use yii\filters\AccessControl;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\Cors;
use yii\rest\ActiveController;

class LiveController extends ActiveController
{
    public $modelClass = 'common\models\Sections';
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
            'only' => ['start-live', 'finish-live', 'get-comments'],
            'rules' => [
                [
                    'actions' => ['start-live', 'finish-live', 'get-comments'],
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

    // شروع پخش زنده یک جلسه توسط مربی
    public function actionStartLive()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه جلسه' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** section id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Sections::findOne(['id' => $post['id'], 'type' => Sections::TYPE_LIVE]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }

        if ($model->mood != Sections::MOOD_READY && $model->mood != Sections::MOOD_PLAYING) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شما اجازه برگزاری این جلسه را ندارید';
            $this->result->response['alert'] = 'section is not ready';
            $this->result->index++;
            return $this->result->response;
        }

        if ($model->status == Sections::STATUS_COMPLETE) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'این جلسه قبلا برگزار شده است';
            $this->result->response['alert'] = 'section is complete';
            $this->result->index++;
            return $this->result->response;
        }

        $model->input_url = Sections::BASIC_INPUT_URL . 'section-' . \Yii::$app->user->id . '-' . $model->id;
        $model->player_url = Sections::BASIC_PLAYER_URL . $model->id;
        $model->hls = Sections::BASIC_HLS . 'section-' . \Yii::$app->user->id . '-' . $model->id . '/playlist.m3u8';
        $model->mood = Sections::MOOD_PLAYING;

        if (!$model->save()) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'خطا در آماده سازی پخش زنده';
            $this->result->response['alert'] = 'failed to playing section';
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->response['data'] = $model->toArray();
        return $this->result->response;
    }

    // اتمام پخش زنده توسط مربی
    public function actionFinishLive()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه جلسه' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** section id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Sections::findOne(['id' => $post['id']]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }

        $uploadSectionVideos = VideoStreams::uploadSectionVideos($post['id']);
        if ($uploadSectionVideos['error']) {
            $model->status = Sections::STATUS_COMPLETE;
            $model->mood = Sections::MOOD_FAILED;
            $model->save(false);

            return $uploadSectionVideos;
        }

        return Sections::addSectionFiles($post['id'], $uploadSectionVideos['data']);
    }

    // دریافت کامنت های یک جلسه لایو
    public function actionGetComments()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه جلسه' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** section id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Sections::findOne(['id' => $post['id']]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }

        $this->result->response['data']['comments'] = Community::find()->where(['parent_id' => $post['id'], 'belong' => Community::BELONG_LIVE])->with('user')
            ->orderBy(['date' => SORT_DESC])->asArray()->all();
        return $this->result->response;
    }
}