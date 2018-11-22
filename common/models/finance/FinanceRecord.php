<?php

namespace common\models\finance;

use Yii;

class FinanceRecord extends \yii\db\ActiveRecord
{
    //状态
    const STATUS_OFF = '0';
    const STATUS_ON = '1';
    public static $STATUS_MAP = [
        self::STATUS_ON  => '已确认',
        self::STATUS_OFF => '未确认',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_finance');
    }
    public static function tableName()
    {
        return 'finance_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'level_id'], 'integer'],
            [['channle', 'operate', 'user', 'parent', 'grandpa', 'team', 'league', 'admin'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['title', 'status'], 'string', 'max' => 50],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'title'      => '名称',
            'user_id'    => '用户ID',
            'price'      => '开单金额',
            'level_id'   => '开单级别',
            'channle'    => '渠道',
            'operate'    => '运营',
            'user'       => '开单者',
            'parent'     => '上级',
            'grandpa'    => '二级',
            'admin'      => '后台管理',
            'team'       => '团队管理',
            'league'     => '团建',
            'status'     => '状态',
            'date'       => '开单时间',
            'created_at' => '创建时间',
        ];
    }
}
