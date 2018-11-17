<?php

namespace common\services\grant;

use common\models\Member\Coupon;
use common\models\Member\MemberAccount;
use common\models\Member\MemberCoupon;
use common\models\Member\MemberRecord;

/**
*  余额消费赠送礼物
 */
class GrantPayBlance extends GrantBase{
    private $scene = "余额消费";
    public $sn = '';  // 订单ID

    /**
     * 判断是否满足条件
     * @param $price  原价
     * @param $condition 条件价格
     *
     * @return bool
     */
    public function isFull($price, $condition){
        return $price > $condition;
    }

    /**
     * 注册赠送积分
     * @param $uid
     * @param $value
     * @param $row
     */
    public function sendGold($uid, $value){
        MemberAccount::addRowList($uid, $value, MemberAccount::ACCOUNT_POINT, MemberRecord::TYPE_POINT, $this->scene, $this->sn);
    }

    /**
     * 注册赠优惠券
     * @param $uid
     * @param $cou_id
     * @param $row
     */
    public function sendCoupon($uid, $cou_id){
        $coupon = Coupon::getCouponByid($cou_id);
        if( empty($coupon) || $coupon->get_method != Coupon::GET_METHOD_PAY ){
            return '优惠券不存在或者获取方式不正确';
        }

        MemberCoupon::addCoupon($uid, $coupon, $this->scene);
    }

    /**
     * 注册赠送现金
     * @param $uid
     * @param $value
     * @param $row
     */
    public function sendCash($uid, $value){
        MemberAccount::addRowList($uid, $value, MemberAccount::ACCOUNT_GIVE, MemberRecord::TYPE_CASH, $this->scene, $this->sn);
    }
}


?>