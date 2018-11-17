<?php

namespace common\models\Crontab;

use Yii;

/**
 * This is the model class for table "crontab_log".
 *
 * @property int $id
 * @property string $crontab_id 任务ID
 * @property string $status 任务运行状态  0正常 1任务报错
 * @property string $execmemory 任务执行消耗内存
 * @property string $exectime 任务执行消耗时间
 * @property string $created_at 执行时间
 */
class CrontabRecord extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'crontab_record';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['exectime'], 'number'],
            [['created_at'], 'safe'],
            [['crontab_id'], 'integer'],
            [['status'], 'string', 'max' => 4],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'crontab_id' => '任务ID',
            'status' => '状态 1成功 其他失败',
            'exectime' => '执行时间',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 创建一条记录信息
     */
    public static function create($taskid, $status, $exectime){
        $tasklog = new static();
        $tasklog->crontab_id = (string)$taskid;
        $tasklog->status = (string)$status;
        $tasklog->exectime = $exectime;
        $tasklog->created_at = date('Y-m-d H:i:s');

        return $tasklog->save(true);
    }
}
