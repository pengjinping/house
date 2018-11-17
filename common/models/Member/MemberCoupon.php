<?php

namespace common\models\Member;

use Yii;

class MemberCoupon extends \yii\db\ActiveRecord
{
    //状态
    const STATUS_INVALID  = 0;
    const STATUS_NOTUSED = 1;
    const STATUS_FREEZING = 2;
    const STATUS_USED = 3;

    public static $STATUS_MAP = [
         self::STATUS_INVALID => '失效',
         self::STATUS_NOTUSED => '未使用',
         self::STATUS_FREEZING => '冻结中',
         self::STATUS_USED => '已使用',
     ];

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'member_coupon';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'coupon_id', 'coupon_type', 'coupon_full', 'coupon_value', 'status'], 'integer'],
            [['expire_time', 'created_at', 'updated_at'], 'safe'],
            [['product_type', 'pickup_scene'], 'string', 'max' => 100],
            [['product_id'], 'string', 'max' => 256],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'coupon_id' => '优惠券ID',
            'product_type' => '产品类型',
            'product_id' => '适用的产品',
            'coupon_type' => '优惠券类型',
            'coupon_full' => '使用条件',
            'coupon_value' => '优惠券值',
            'expire_time' => '过期时间',
            'status' => '状态',
            'pickup_scene' => '场景编码',
            'created_at' => '新增时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 判断优惠券是否达到领取上线
     * @param     $uid  用户ID
     * @param     $cou_id 优惠券ID
     * @param int $limit  领取限制
     *
     * @return bool|string
     */
    public static function isGetExists($uid, $cou_id, $limit=1){
        $count = static::find()->where(['user_id' => $uid, 'coupon_id' => $cou_id])->count();
        if($limit > 0 && $count >= $limit){
            return '已经达到领取上线';
        }
        return true;
    }

    public static function addCoupon($uid, $coupon, $scene){
        $res = self::isGetExists($uid, $coupon->id, $coupon->limit);
        if($res !== true){
            return $res;
        }

        $newUserCoupon = new static();
        $newUserCoupon->user_id = $uid;
        $newUserCoupon->coupon_id = $coupon->id;
        $newUserCoupon->product_type = $coupon->product_type;
        $newUserCoupon->product_id = $coupon->product_id;
        $newUserCoupon->coupon_type = $coupon->type;
        $newUserCoupon->coupon_full = $coupon->full;
        $newUserCoupon->coupon_value = $coupon->value;
        $newUserCoupon->expire_time = date("Y-m-d 23:59:59", strtotime("+{$coupon->expire_days} days"));
        $newUserCoupon->status = self::STATUS_NOTUSED;
        $newUserCoupon->pickup_scene = $scene;
        $newUserCoupon->created_at = date('Y-m-d H:i:s');
        $newUserCoupon->updated_at = date('Y-m-d H:i:s');

        $coupon->pick_count += 1;

        if($newUserCoupon->save() && $coupon->save() ){
            return true;
        }else{
            return $newUserCoupon->errors[0];
        }
    }
}
