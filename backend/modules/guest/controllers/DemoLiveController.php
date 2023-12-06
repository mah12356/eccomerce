<?php

namespace backend\modules\guest\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use common\components\VideoStreams;
use common\models\Diet;
use common\models\Factor;
use common\models\Regimes;
use common\models\Register;
use common\models\Sections;
use common\models\SignupForm;
use common\models\User;
use yii\helpers\ArrayHelper;
use yii\rest\ActiveController;

class DemoLiveController extends ActiveController
{
    public $modelClass = 'common\models\Sections';
    public object $result;

    public function beforeAction($action)
    {
        $this->result = new Tools();
        return parent::beforeAction($action);
    }
    public function actionStartLive()
    {
        $post = Gadget::setPost();

        if (!isset($post['date']) || !$post['date']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'date' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** date **';
            $this->result->index++;
        }
        if (!isset($post['input_url']) || !$post['input_url']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'input_url' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** input_url **';
            $this->result->index++;
        }
        if (!isset($post['hls']) || !$post['hls']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'hls' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** hls **';
            $this->result->index++;
        }
        if ($this->result->response['error']){
            return $this->result->response;
        }

        $model = Sections::findOne(['date' => $post['date'], 'type' => Sections::TYPE_LIVE]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }

        $model->input_url = $post['input_url'];
        $model->player_url = Sections::BASIC_PLAYER_URL . $model->id;
        $model->hls = $post['hls'];
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

    public function actionFinishLive()
    {
        $post = Gadget::setPost();

        if (!isset($post['date']) || !$post['date']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'تاریخ شروع' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** date **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Sections::findOne(['date' => $post['date'], 'type' => Sections::TYPE_LIVE]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = Message::MODEL_NOT_FOUND;
            $this->result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $this->result->index++;
            return $this->result->response;
        }

        $uploadSectionVideos = VideoStreams::uploadSectionVideos($model->id);
        if ($uploadSectionVideos['error']) {
            $model->status = Sections::STATUS_COMPLETE;
            $model->mood = Sections::MOOD_FAILED;
            $model->save(false);

            return $uploadSectionVideos;
        }

        return Sections::addSectionFiles($model->id, $uploadSectionVideos['data']);
    }

    public function actionAssignRegime()
    {
        $post = Gadget::setPost();

        if (!isset($post['user_id']) || !$post['user_id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'user_id' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** user_id **';
            $this->result->index++;
        }
        if (!isset($post['age']) || !$post['age']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'age' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** age **';
            $this->result->index++;
        }
        if (!isset($post['height']) || !$post['height']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'height' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** height **';
            $this->result->index++;
        }
        if (!isset($post['weight']) || !$post['weight']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'weight' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** weight **';
            $this->result->index++;
        }
        if (!isset($post['gender']) || !$post['gender']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'gender' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** gender **';
            $this->result->index++;
        }
        if (!isset($post['activity']) || !$post['activity']) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'activity' . Message::MISSING_PARAMETER;
            $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** activity **';
            $this->result->index++;
        }
        if ($this->result->response['error']){
            return $this->result->response;
        }

        if ($post['gender'] == 'male') {
            $basicEnergy = 66.47 + (13.75 * $post['weight']) + (5 * $post['height']) - (6.76 * $post['age']);
        }else {
            $basicEnergy = 65.5 + (9.56 * $post['weight']) + (1.85 * $post['height']) - (4.68 * $post['age']);
        }

        if ($post['activity'] == 'low') {
            $calorie = $basicEnergy * 1.375;
        }elseif ($post['activity'] == 'medium') {
            $calorie = $basicEnergy * 1.6;
        }else {
            $calorie = $basicEnergy * 1.8;
        }

        $calorie = round((int)$calorie , -2);

        if ($calorie < 1200) {
            $calorie = 1200;
        }
        if ($calorie > 1800) {
            $calorie = 1800;
        }

        $diets = Diet::find()->where(['user_id' => $post['user_id'], 'package_id' => 0, 'course_id' => 0,
            'status' => Diet::STATUS_REGIME_UPLOADED])->asArray()->all();

        if ($diets) {
            $regimes = Regimes::find()->where(['NOT IN', 'id', ArrayHelper::map($diets, 'regime_id', 'regime_id')])->andWhere(['calorie' => $calorie, 'status' => Regimes::STATUS_ACTIVE])->asArray()->all();
        }else {
            $regimes = Regimes::find()->where(['calorie' => $calorie, 'status' => Regimes::STATUS_ACTIVE])->asArray()->all();
        }

        $model = new Diet();

        $model->user_id = $post['user_id'];
        $model->package_id = 0;
        $model->course_id = 0;
        $model->date = Jdf::jmktime();
        $model->date_update = Jdf::jmktime();

        if ($regimes) {
            $pr = rand(0, count($regimes) - 1);
            $model->regime_id = $regimes[$pr]['id'];
            $model->status = Diet::STATUS_REGIME_UPLOADED;
            $model->date_update = Jdf::jmktime();
        }else {
            $model->status = Diet::STATUS_REGIME_NOT_FOUND;
        }

        if (!$model->save()) {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'رکورد رژیم ذخیره نشد';
            $this->result->response['alert'][$this->result->index] = 'regime record not save';
            $this->result->index++;
            return $this->result->response;
        }

        if ($model->status == Diet::STATUS_REGIME_UPLOADED) {
            $regime = Diet::find()->where(['id' => $model->id])->with('regime')->asArray()->one();

            $this->result->response['data'] = $regime;
        }else {
            $this->result->response['error'] = true;
            $this->result->response['message'][$this->result->index] = 'رژیم یافت نشد';
            $this->result->response['alert'][$this->result->index] = 'regime not set';
            $this->result->index++;
        }

        return $this->result->response;
    }

    public function actionDietRemove()
    {
        $post = Gadget::setPost();

        if (isset($post['id']) && $post['id']) {
            Diet::deleteAll(['id' => $post['id']]);
        }
    }
}