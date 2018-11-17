<?php

namespace common\models\Crontab;

use Yii;
use common\helpers\CronHelper;

/**
 * This is the model class for table "crontab".
 *
 * @property int $id
 * @property string $name 定时任务名称
 * @property string $route 任务路由
 * @property string $crontab_str crontab格式
 * @property int $switch 任务开关 0关闭 1开启
 * @property string $last_rundate 上次任务运行时间
 * @property string $next_rundate 下次任务执行时间
 */
class Crontab extends \yii\db\ActiveRecord
{
    // switch字段的文字映射
    const SWITCH_ON  = 1;
    const SWITHC_OFF = 0;

    public static $switchTextMap = [
        self::SWITHC_OFF => '关闭',
        self::SWITCH_ON  => '开启',
    ];

    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->get("common");
    }
    public static function tableName()
    {
        return 'crontab';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['switch'], 'integer'],
            [['last_rundate', 'next_rundate'], 'safe'],
            [['name'], 'string', 'max' => 30],
            [['route', 'crontab_str', 'remarks'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id'           => 'ID',
            'name'         => '任务名称',
            'route'        => '任务路由',
            'crontab_str' => 'crontab格式',
            'switch'       => '任务开关',
            'remarks'      => '任务描述',
            'last_rundate'=> '上次任务运行时间',
            'next_rundate'=> '下次任务执行时间',
        ];
    }

    /**
     * 计算下次运行时间
     */
    public function getNextRunDate()
    {
        $this->last_rundate = date('Y-m-d H:i:s');
        $this->next_rundate = CronHelper::formatToDate($this->crontab_str);
        $this->save();
    }
}
