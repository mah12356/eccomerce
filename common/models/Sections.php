<?php

namespace common\models;

use common\components\Alerts;
use common\components\Gadget;
use common\components\Jdf;
use common\components\Message;
use common\components\Tools;
use Yii;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "sections".
 *
 * @property int $id
 * @property int $group
 * @property string $type
 * @property string $title
 * @property int $date
 * @property int $start_at
 * @property int $end_at
 * @property string $input_url
 * @property string $player_url
 * @property string $hls
 * @property string $status
 * @property string $mood
 */
class Sections extends \yii\db\ActiveRecord
{
    const TYPE_LIVE = 'live'; // برگزاری به صورت لایو
    const TYPE_OFFLINE = 'offline'; // برگزاری به صورت ویدیو آماده

    const STATUS_PROCESS = 'processing'; // جلسه تعریف شده
    const STATUS_COMPLETE = 'complete'; // اتمام جلسه

    const MOOD_PENDING = 'pending'; // در انتظار پخش
    const MOOD_READY = 'ready'; // آماده پخش
    const MOOD_PLAYING = 'playing'; // در حال پخش
    const MOOD_COMPLETE = 'complete'; // اتمام جلسه و ذخیره شده
    const MOOD_FAILED = 'failed'; // اتمام جلسه و ذخیره نشده

    const BASIC_INPUT_URL = 'rtmp://azhman.azhman.online:1935/azhman2/';
    const BASIC_PLAYER_URL = 'https://mahsaonlin.com/packages/section-content?id=';
    const BASIC_HLS = 'http://azhman.azhman.online:1935/azhman2/';

    public $start_date;
    public $count;
    public $interval;

    public $file;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'sections';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['group', 'title', 'type', 'date', 'start_at', 'end_at'], 'required'],
            [['group', 'date'], 'integer'],
            [['title', 'type', 'input_url', 'player_url', 'hls', 'status'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'type' => Yii::t('app', 'نوع جلسات'),
            'title' => Yii::t('app', 'عنوان جلسه'),
            'date' => Yii::t('app', 'تاریخ شروع جلسه'),
            'start_at' => Yii::t('app', 'ساعت شروع جلسه'),
            'end_at' => Yii::t('app', 'ساعت پایان جلسه'),
            'status' => Yii::t('app', 'وضعیت'),
            'mood' => Yii::t('app', 'وضعیت جلسه'),
            'start_date' => Yii::t('app', 'تاریخ شروع جلسات'),
            'count' => Yii::t('app', 'تعداد جلسات'),
            'interval' => Yii::t('app', 'تعداد روزهای بین هر جلسه'),
        ];
    }

    /**
     * {@inheritdoc}
     * @return SectionsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new SectionsQuery(get_called_class());
    }

    public static function getType(): array
    {
        return [
            self::TYPE_LIVE => 'برگزاری به صورت لایو',
            self::TYPE_OFFLINE => 'برگزاری به صورت ویدیو آماده',
        ];
    }

    public static function getStatus()
    {
        return [
            self::STATUS_PROCESS => 'جلسه تعریف شده',
            self::STATUS_COMPLETE => 'اتمام جلسه',
        ];
    }

    public static function getMood()
    {
        return [
            self::MOOD_PENDING => 'در انتظار پخش',
            self::MOOD_READY => 'آماده پخش',
            self::MOOD_PLAYING => 'در حال پخش',
            self::MOOD_COMPLETE => 'اتمام جلسه و ذخیره شده',
            self::MOOD_FAILED => 'اتمام جلسه و ذخیره نشده',
        ];
    }

    public function defineSections($group, $days): array
    {
        $result = new Tools();
        $transaction = Yii::$app->db->beginTransaction();

        $group = Groups::findOne(['id' => $group]);
        if (!$group) {
            $result->response['error'] = true;
            $result->response['message'][$result->index] = '0';
            $result->response['alert'][$result->index] = '0';
            $result->index++;
            return $result->response;
        }

        try {
            $i = 1;
            $nextSectionDate = $group->start_date;
            while ($i <= $group->sections_count) {
                if (in_array(Jdf::jdate('l', $nextSectionDate), $days)) {
                    $explodeDate = explode('/', Jdf::jdate('Y/m/d', $nextSectionDate));
                    $explodeStart = explode(':', $group->start_time);
                    $explodeEnd = explode(':', $group->finish_time);

                    $model = new Sections();

                    $model->group = $group->id;
                    $model->type = $group->type;
                    $model->title = 'جلسه ' . $i;
                    $model->date = $nextSectionDate;
                    $model->start_at = Jdf::jmktime((int)$explodeStart[0], (int)$explodeStart[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);
                    $model->end_at = Jdf::jmktime((int)$explodeEnd[0], (int)$explodeEnd[1], 0, $explodeDate[1], $explodeDate[2], $explodeDate[0]);

                    if (!$model->save()) {
                        $transaction->rollBack();
                        $result->response['error'] = true;
                        $result->response['message'][$result->index] = '1';
                        $result->response['alert'][$result->index] = '1';
                        $result->index++;
                        return $result->response;
                    }
                    $i++;
                }
                $nextSectionDate += 86400;
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

    public static function readySection($coach_id)
    {
        $current = Jdf::jmktime();
        $today = Jdf::jmktime(0, 0, 0, Gadget::convertToEnglish(Jdf::jdate('m', $current)), Gadget::convertToEnglish(Jdf::jdate('d', $current)), Gadget::convertToEnglish(Jdf::jdate('Y', $current)));
        $model = Packages::find()->where(['coach_id' => $coach_id, 'status' => Packages::STATUS_READY])->with('courses.contains.all')->all();
        foreach ($model as $item) {
            foreach ($item->courses as $course) {
                foreach ($course->contains as $contain) {
                    foreach ($contain->all as $section) {
                        if ($today == $section->date) {
                            if ($section->mood != Sections::MOOD_PLAYING && $section->mood != Sections::MOOD_READY && $section->mood != Sections::MOOD_COMPLETE) {
                                $section->mood = Sections::MOOD_READY;
                                $section->save(false);
                            }
                        }
                    }
                }
            }
        }
    }

    public static function addSectionFiles($id, $uploadedFile): array
    {
        $result = new Tools();

        $model = Sections::findOne(['id' => $id]);
        if (!$model) {
            $result->response['error'] = true;
            $result->response['message'] = Message::MODEL_NOT_FOUND;
            $result->response['alert'] = Alerts::MODEL_NOT_FOUND;
            $result->index++;
            return $result->response;
        }

        $model->status = Sections::STATUS_COMPLETE;
        $model->mood = Sections::MOOD_COMPLETE;

        if ($model->save()) {
            return SectionContents::defineRecord($id, $uploadedFile);
        }else {
            $result->response['error'] = true;
            $result->response['message'] = Message::FAILED_TO_EXECUTE;
            $result->response['alert'] = Alerts::PROCESS_INCOMPLETE;
            $result->index++;
            return $result->response;
        }
    }

    public function getFirstSection(): ActiveQuery
    {
        return $this->hasOne(Sections::className(), ['group' => 'group'])->orderBy(['id' => SORT_ASC]);
    }
    public function getLastSection(): ActiveQuery
    {
        return $this->hasOne(Sections::className(), ['group' => 'group'])->orderBy(['id' => SORT_DESC]);
    }
    public function getContent(): ActiveQuery
    {
        return $this->hasMany(SectionContents::className(), ['section_id' => 'id']);
    }
    public function getGroup()
    {
        return $this->hasOne(Groups::className(), ['id' => 'group']);
    }
}
