<?php

namespace common\models\Member;

use Yii;

class Grant extends \yii\db\ActiveRecord
{
    //触发事件
    const EVENT_REGIEST = 1;
    const EVENT_LOGIN = 2;
    const EVENT_IMPULSE = 3;
    const EVENT_PAY_ONLINE =4;
    const EVENT_PAY_BLANCE = 5;
    public static $EVENT_MAP = [
         self::EVENT_REGIEST    => '注册',
         self::EVENT_LOGIN      => '登录',
         self::EVENT_IMPULSE    => '充值',
         self::EVENT_PAY_ONLINE => '在线消费',
         self::EVENT_PAY_BLANCE => '余额消费',
     ];

    //赠送类型
    const TYPE_CREADIT = 1;
    const TYPE_COUPON = 2;
    const TYPE_CASH   = 3;
    const TYPE_DAYTIME = 4;
    public static $TYPE_MAP = [
         self::TYPE_CREADIT => '积分',
         self::TYPE_COUPON => '优惠券',
         self::TYPE_CASH => '现金',
         self::TYPE_DAYTIME => '时间',
     ];

    //赠送方式
    const METHOD_VALUE = 1;
    const METHOD_RATE  = 2;
    public static $METHOD_MAP = [
         self::METHOD_VALUE => '价值',
         self::METHOD_RATE => '比例',
     ];

    //状态
    const STATUS_ON = 1;
    const STATUS_OFF = 0;
    public static $STATUS_MAP = [
         self::STATUS_ON  => '开启',
         self::STATUS_OFF => '关闭',
     ];

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'grant';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['event', 'type', 'method', 'value', 'value_id', 'condition', 'status'], 'integer'],
            [['start_date', 'end_date', 'created_at', 'updated_at'], 'safe'],
            [['desc_txt'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'event' => '触发事件',
            'type' => '赠送类型',
            'method' => '赠送方式',
            'value' => '赠送价值（比例%）：如：注册赠送5个积分、充值赠送1%优惠',
            'value_id' => '类型为优惠券时有效 优惠券ID',
            'condition' => '赠送条件',
            'status' => '状态',
            'desc_txt' => '备注描述',
            'start_date' => '开始时间',
            'end_date' => '结束时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     *  获取事件发放奖励
     * @param $event 触发事件
     *
     * @return array
     */
    public static function getListByEvent($event){
        return static::find()
            ->select("id,type,method,value,value_id,condition")
            ->where(['status' => self::STATUS_ON])
            ->andWhere(['<', 'start_date', date('Y-m-d H:i:s')])
            ->andWhere(['>', 'end_date',   date('Y-m-d H:i:s')])
            ->andWhere(['event' => $event])
            ->asArray()
            ->all();
    }
}
