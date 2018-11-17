<?php

namespace common\models\Member;

use Yii;

class Coupon extends \yii\db\ActiveRecord
{
    // 状态
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    public static $STATUS_MAP = [
        self::STATUS_ON  => '开启',
        self::STATUS_OFF => '关闭',
    ];

    //优惠券类型
    const TYPE_DEDUCE = 1;
    const TYPE_DISCOUNT = 2;
    public static $TYPE_MAP = [
        self::TYPE_DEDUCE   => '抵扣券',
        self::TYPE_DISCOUNT => '折扣券',
    ];

    //适用的类型
    const PRODUCT_TYPE_TEST = '考试';
    const PRODUCT_TYPE_MOVIE = '视频';
    const PRODUCT_TYPE_WORD = '文档';
    const PRODUCT_TYPE_SHOP = '商品';
    public static $PRODUCT_MAP = [
        self::PRODUCT_TYPE_TEST  => '考试',
        self::PRODUCT_TYPE_MOVIE => '视频',
        self::PRODUCT_TYPE_WORD  => '文档',
        self::PRODUCT_TYPE_SHOP  => '商品',
    ];

    //获取方法
    const GET_METHOD_CODE = '1';
    const GET_METHOD_SYSTEM = '2';
    const GET_METHOD_PAY = '3';
    const GET_METHOD_REGIEST = '4';
    const GET_METHOD_IMPULSE = '5';
    const GET_METHOD_LOGIN = '6';
    public static $GET_METHOD_MAP = [
        self::GET_METHOD_CODE    => '优惠码获取',
        self::GET_METHOD_SYSTEM  => '系统赠送',
        self::GET_METHOD_PAY     => '消费获取',
        self::GET_METHOD_REGIEST => '注册赠送',
        self::GET_METHOD_LOGIN   => '登录赠送',
        self::GET_METHOD_IMPULSE => '充值赠送',
     ];

    //删除标记
    const DEL_FLAG_OFF = 0;
    const DEL_FLAG_ON = 1;
    public static $DEL_FLAG_MAP = [
        self::DEL_FLAG_OFF => '未删除',
        self::DEL_FLAG_ON  => '已删除',
     ];

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['expire_days', 'total_count', 'pick_count', 'limit', 'type', 'full', 'value', 'get_method', 'del_flag', 'status'], 'integer'],
            [['begin_time', 'expire_time', 'created_at', 'updated_at'], 'safe'],
            [['name'], 'string', 'max' => 50],
            [['desc_txt'], 'string', 'max' => 1000],
            [['product_type', 'product_id'], 'string', 'max' => 255],
            [['code'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'desc_txt' => '描述',
            'expire_days' => '有效期(天)',
            'total_count' => '总量',
            'pick_count' => '已领取',
            'limit' => '每人限领',
            'type' => '类型',
            'full' => '满多少可用',
            'value' => '券值(折扣)',
            'product_type' => '适用类型',
            'product_id' => '适用产品',
            'get_method' => '获取方法',
            'code' => '优惠码',
            'del_flag' => '删除标记',
            'status' => '状态',
            'begin_time' => '开始时间',
            'expire_time' => '过期时间',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     *  设置code
     *
     */
    public function setCode(){
        $code = date('YmdHis');
        $str = "ABCDEFGHIJKLMNOPQRESTUVWXYZ";
        for($i=0; $i < 6; $i++){
            $code = substr_replace($code, $str[rand(0,26)], rand(2*$i, 3+2*$i), 1);
        }

        $this->code = substr($code, rand(0,4), 10);
    }

    /**
     *  获取优惠券明细
     * @param $id 优惠券ID
     *
     * @return array
     */
    public static function getCouponByid($id){
        return static::find()
            ->where(['status' => self::STATUS_ON, 'del_flag' => self::DEL_FLAG_OFF])
            ->andWhere(['<=', 'begin_time',  date('Y-m-d')])
            ->andWhere(['>=', 'expire_time', date('Y-m-d')])
            ->andWhere("`pick_count` < `total_count`")
            ->andWhere(['id' => $id])
            ->one();
    }

    /**
     *  获取不同类型的优惠券
     * @param $event 触发事件
     *
     * @return array
     */
    public static function getListByMethod($method){
        return static::find()
            ->where(['status' => self::STATUS_ON, 'del_flag' => self::DEL_FLAG_OFF])
            ->andWhere(['<=', 'begin_time',  date('Y-m-d')])
            ->andWhere(['>=', 'expire_time', date('Y-m-d')])
            ->andWhere("`pick_count` < `total_count`")
            ->andWhere(['get_method' => $method])
            ->asArray()
            ->all();
    }
}
