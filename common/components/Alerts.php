<?php

namespace common\components;

class Alerts
{
    const MISSING_PARAMETER = 'missing required parameter';
    const PROCESS_DONE = 'successfully execute process';
    const PROCESS_INCOMPLETE = 'failed to execute process';
    const WRONG_MOBILE = 'wrong mobile format';
    const USER_NOT_FOUND = 'user not found';
    const MODEL_NOT_FOUND = 'model not found';
    const DUPLICATE_MOBILE = 'phone number is duplicated';
    const FILE_NOT_SENT = 'file not sent';
    const UPLOAD_FAILED = 'upload failed';
    const WRONG_VERIFY_CODE = 'wrong verify code';
    const UNKNOWN_ERROR = 'unknown error happened';

    const VALIDATING_ACCOUNT_INFO = 'اطلاعات شما در حال بررسی است';

    const ERROR_MODEL_ERROR = 'متاسفانه در ذخیره خطایی وجود دارد';
    const ERROR_MODEL_SAVE = 'متاسفانه در ذخیره داده مشکلی وجود دارد';
    const SUCCESS = 'با موفقیت انجام شد';
    const VALIDATE_NOT = 'متاسفانه فرمت داده ها مشکل دارد دوباره سعی کنید';
    const FILE_NOT_UPLOAD = 'متاسفانه فایل مورد نظر آپلود نشد دوباره تلاش کنید';
    const IMAGE_NOT_UPLOAD = 'متاسفانه عکس مورد نظر آپلود نشد دوباره تلاش کنید';
    const AUTH_NOT = 'متاسفانه شما حق دسترسی ندارید';
}