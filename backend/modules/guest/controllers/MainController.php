<?php

namespace backend\modules\guest\controllers;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use common\models\Chats;
use common\models\Community;
use common\models\Factor;
use common\models\Register;
use common\models\Sections;
use common\models\Tickets;
use yii\rest\ActiveController;
use common\models\User;

class MainController extends ActiveController
{
    public $modelClass = 'common\modules\main\models\Config';
    public object $result;

    public function beforeAction($action)
    {
        $this->result = new Tools();
        return parent::beforeAction($action);
    }


    public function actionUploadAudio()
    {
        $post = Gadget::setPost();
        if ($post) {
            if (isset($post['id']) && $post['id'] && isset($_FILES['audio']) && $_FILES['audio']) {
                $ticket = Tickets::findOne(['id' => $post['id']]);
                if ($ticket) {
                    $model = new Chats();

                    $model->parent_id = $ticket->id;
                    $model->belong = Chats::BELONG_TICKET;
                    $model->sender = Chats::SENDER_ADMIN;

                    try {
                        $upload = Gadget::phpUploadFile($_FILES['audio'], ['mp3'], $model->parent_id . '_audio_' . Jdf::jmktime(), Chats::UPLOAD_PATH);
                        if (!$upload['error']) {
                            $model->audio = $upload['path'];
                            if ($model->saveMessage()) {
                                return [
                                    'error' => false,
                                ];
                            }else {
                                return [
                                    'error' => true,
                                ];
                            }
                        }else {
                            return [
                                'error' => true,
                            ];
                        }
                    }catch (\Exception $exception) {
                        return [
                            'error' => true,
                        ];
                    }
                }else {
                    return [
                        'error' => true,
                    ];
                }
            }else {
                return [
                    'error' => true,
                ];
            }
        }else {
            return [
                'error' => true,
            ];
        }
    }


    // بارگذاری هر نوع فایلی در پوشه tmp
    public function actionUpload()
    {
            //    set_time_limit(0);

//        return $_FILES;
        if ($_FILES) {
            if (isset($_FILES['data'])) {
                $post = Gadget::setPost();
                if (!isset($post['fileType']) || !(array)$post['fileType'] || count((array)$post['fileType']) == 0) {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = 'نوع فایل' . Message::MISSING_PARAMETER;
                    $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** extensions **';
                    $this->result->index++;
                    return $this->result->response;
                }

                switch ($post['fileType']) {
                    case 'image':
                        $extensions = ['jpg', 'jpeg', 'webp', 'png'];
                        break;
                    case 'video':
                        $extensions = ['mp4'];
                        break;
                    case 'audio':
                        $extensions = ['mp3', 'm4a', 'mp4'];
                        break;
                    case 'document':
                        $extensions = ['pdf'];
                        break;
                    default:
                        $extensions = [];
                        break;
                }
//                return $_FILES['data'];
                $upload = Gadget::phpUploadFile($_FILES['data'], (array)$extensions);
                if (!$upload['error']) {
                    $this->result->response['data']['file'] = $upload['path'];
                } else {
                    $this->result->response['error'] = true;
                    $this->result->response['message'][$this->result->index] = $upload['message'];
                    $this->result->response['alert'][$this->result->index] = Alerts::UPLOAD_FAILED;
                    $this->result->index++;
                }
            } else {
                $this->result->response['error'] = true;
                $this->result->response['message'][$this->result->index] = 'اطلاعات فایل' . Message::MISSING_PARAMETER;
                $this->result->response['alert'][$this->result->index] = Alerts::MISSING_PARAMETER . ' ** data **';
                $this->result->index++;
            }
        } else {
            $this->result->response['error'][$this->result->index] = true;
            $this->result->response['message'][$this->result->index] = Message::FILE_NOT_SENT;
            $this->result->response['alert'][$this->result->index] = Alerts::FILE_NOT_SENT;
            $this->result->index++;
        }

        return $this->result->response;
    }
    // ثبت فاکتور در درگاه برای شروع پرداخت
    public function actionPayFactor()
    {
        $post = Gadget::setPost();

        if (!isset($post['id']) || !$post['id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه فاکتور' . Message::MISSING_PARAMETER;
            $this->result->response['alert'] = Alerts::MISSING_PARAMETER . ' ** factor id **';
            $this->result->index++;
            return $this->result->response;
        }

        $model = Factor::findOne(['id' => $post['id']]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'فاکتور مورد نظر شما یافت نشد';
            $this->result->response['alert'] = 'factor not found';
            $this->result->index++;
            return $this->result->response;
        }

        return $this->result->response;
    }
    // بررسی نتیجه پرداخت
    public function actionPaymentResult()
    {
        $model = Factor::findOne(['response_key' => $_GET['auth']]);
        if (!$model) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'فاکتور مورد نظر شما یافت نشد';
            $this->result->response['alert'] = 'factor not found';
            $this->result->index++;
            return $this->result->response;
        }

        $payment = true;

        return Register::enrollPayment($payment, $model->id);
    }

    public function actionSendLiveComment()
    {
        $post = Gadget::setPost();

        if (!isset($post['section_id']) || !$post['section_id']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'شناسه جلسه ارسال نشد';
            $this->result->response['alert'] = 'section Id not found';
            $this->result->index++;
            return $this->result->response;
        }

        if (!isset($post['text']) || !$post['text']) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'متن کامنت خالی است';
            $this->result->response['alert'] = 'empty text';
            $this->result->index++;
            return $this->result->response;
        }

        $user = '';

        if (isset($post['user_id']) && $post['user_id']) {
            $user = User::findOne(['id' => $post['user_id']]);
        }
        if (isset($post['auth']) && $post['auth']) {
            $user = User::findOne(['auth_key' => $post['auth']]);
        }

        $section = Sections::findOne(['id' => $post['section_id']]);
        if (!$section) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'جلسه مورد نظر یافت نشد';
            $this->result->response['alert'] = 'section not found';
            $this->result->index++;
            return $this->result->response;
        }

        $model = new Community();

        if ($user) {
            $model->user_id = $user->id;
        }else {
            $model->user_id = 3991;
        }
        $model->parent_id = $post['section_id'];
        $model->belong = Community::BELONG_LIVE;
        $model->text = $post['text'];
        $model->date = Jdf::jmktime();
        $model->status = Community::STATUS_SUBMIT;

        if (!$model->save(false)) {
            $this->result->response['error'] = true;
            $this->result->response['message'] = 'خطا در ثبت کامنت';
            $this->result->response['alert'] = 'comment not save';
            $this->result->index++;
        }

        return $this->result->response;
    }
}