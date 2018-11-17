<?php

namespace common\services\grant;

/**
* 赠送礼物
 */
abstract class GrantBase{
    abstract public function isFull($price, $condition);   // 是否满足赠送条件
    abstract public function sendGold($uid, $value);    // 赠送积分
    abstract public function sendCoupon($uid, $cou_id); // 优惠券ID
    abstract public function sendCash($uid, $value);    // 赠送金额

    // 普通方法（非抽象方法）
    protected function printOut($param) {
        print_r($param);
    }
}
?>