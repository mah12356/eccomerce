<?php
namespace common\components;

use common\models\Sections;

class VideoStreams
{
    /**
     * get all saved videos on live server
     * @return array
     */
    public static function getVideosList(): array
    {
        $result = new Tools();

        $url = "http://azhman.azhman.online/admin/api/guest/files";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($ch);
        $videos = json_decode($json, true);
        if (!$videos) {
            $result->response['error'] = true;
            $result->response['message'] = "در حال حال حاظر دسترسی به فیلم کلاس ها امکان پذیر نمی باشد";
            return $result->response;
        }

        $result->response['data'] = $videos['data']['files'];
        return $result->response;
    }

    /**
     * find a single sections videos
     * @param int $section_id
     * @return array
     */
    public static function findSectionVideos(int $section_id): array
    {
        $result = new Tools();

        $model = Sections::findOne(['id'=> $section_id]);
        $videos = self::getVideosList();
        if ($videos['error'] || !$model) {
            $result->response['error'] = true;
            $result->response['message'] = 'خطا در یافتن اطلاعات جلسه یا ویدیوها';
            $result->response['alert'] = 'section or videos not found';
            return $result->response;
        }

        $explodedInputUrl = explode('/', $model->input_url);
        if (!isset($explodedInputUrl[4]) || !$explodedInputUrl[4]) {
            $result->response['error'] = true;
            $result->response['message'] = 'فرمت اشتباه در input url';
            $result->response['alert'] = 'wrong input url format';
            return $result->response;
        }

        $sectionVideos = [];
        foreach ($videos['data'] as $item) {
            $explodedVideo = explode('.', $item);
            if (!isset($explodedVideo[0]) || !$explodedVideo[0]) {
                $result->response['error'] = true;
                $result->response['message'] = 'نام فایل ویدیوای معتبر نیست';
                $result->response['alert'] = 'video file name it is not valid';
                return $result->response;
            }
            $explodedVideoName = explode('_', $explodedVideo[0]);
            if (!isset($explodedVideoName[0]) || !$explodedVideoName[0]) {
                $result->response['error'] = true;
                $result->response['message'] = 'نام فایل دارای فرمت اشتباه است';
                $result->response['alert'] = 'wrong file name format';
                return $result->response;
            }
            if ($explodedVideoName[0] == $explodedInputUrl[4]) {
                if (isset(explode('_', $explodedVideo[0])[1])) {
                    $sectionVideos[(int)(explode('_', $explodedVideo[0])[1]) + 1] = $item;
                }else {
                    $sectionVideos[0] = $item;
                }
            }
        }

        if (!$sectionVideos) {
            $result->response['error'] = true;
            $result->response['message'] = 'فایلی برای ذخیره سازی یافت نشد';
            $result->response['alert'] = 'no video file exist';
            $result->response['data'] = ['section' => $model];
            return $result->response;
        }

        $result->response['data'] = $sectionVideos;
        return $result->response;

    }

    /**
     * compare local video and live server videos sizes
     * @param string $url
     * @param $file
     * @return bool
     */
    public static function compareFileSize(string $url, $file): bool
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_TIMEOUT,6000);
        curl_setopt($ch,CURLOPT_URL, $url);
        curl_setopt($ch,CURLOPT_NOBODY,true);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_SSL_VERIFYHOST,false);
        curl_exec($ch);

        $response = curl_getinfo($ch);
        if (!isset($response['download_content_length']) || !$response['download_content_length']) {
            return false;
        }
        $fileSize = $response['download_content_length'];

        curl_close($ch);

        $downloadSize = filesize($file);
        if ($downloadSize == $fileSize) {
            return true;
        }else {
            return false;
        }
    }

    /**
     * download a video on server to localhost
     * @param int $section_id
     * @param $video
     * @return bool
     */
    public static function downloadSectionVideos(int $section_id , $video): bool
    {
        $model = Sections::findOne(['id' => $section_id]);

        set_time_limit(0);
        ini_set('max_execution_time', '36000');
        try {
            if (!is_dir( \Yii::getAlias('@upload') . '/sections/' . $model->group . '/')) {
                mkdir(\Yii::getAlias('@upload') . '/sections/' . $model->group);
            }
            $url = 'http://azhman.azhman.online/upload/' . $video;
            $path = \Yii::getAlias('@upload') . '/sections/' . $model->group . '/' . $video;
            if (file_exists($path)) {
                unlink($path);
            }
            $fp = fopen($path, 'a+',true);
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 6000);
            curl_setopt($ch, CURLOPT_FILE, $fp);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_exec($ch);
            curl_close($ch);
            fclose($fp);

            // return true;

             if (self::compareFileSize($url, $path)) {
                 return true;
             }else {
                 return false;
             }
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * get a section id after live end and start to upload videos to localhost
     * @param int $section_id
     * @return array
     */
    public static function uploadSectionVideos(int $section_id): array
    {
        $result = new Tools();

        $model = Sections::findOne(['id' => $section_id]);
        if (!$model) {
            $result->response['error'] = true;
            $result->response['message'] = 'جلسه‌ای یافت نشد';
            $result->response['alert'] = 'section not found';
            $result->response['data'] = ['section' => $model];
            return $result->response;
        }

        $response = self::findSectionVideos($section_id);
        if ($response['error']) {
            return $response;
        }

        $savedVideos = [];
        foreach ($response['data'] as $video) {
            if (self::downloadSectionVideos($section_id, $video)) {
                $savedVideos[] = $video;
            }
        }

        if (count($response['data']) != count($savedVideos)) {
            foreach ($savedVideos as $item) {
                Gadget::deleteFile($item, 'sections/' . $model->group);
            }
            $result->response['error'] = true;
            $result->response['message'] = 'خطا در ذخیره سازی فایل های ویدیویی';
            $result->response['alert'] = 'failed to save section videos';
        }else {
            $result->response['data'] = $savedVideos;
        }

        return $result->response;
    }
}