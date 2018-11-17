<?php

namespace common\services;

use common\helpers\RedisHelper;
use \common\models\Member\Grant;
use common\services\grant\GrantImpulse;
use common\services\grant\GrantLogin;
use common\services\grant\GrantPayBlance;
use common\services\grant\GrantPayOnline;
use common\services\grant\GrantRegist;

/**
 * 用户的赠送服务
 *  如：注册、登录、消费、充值等事件触发 给用户赠送积分、优惠券、现金等
 */
class GrantService
{
    /**
     * @param $uid  用户ID
     * @param $event 触发事件
     * @param $price 消费赠送时 消费金额【分】
     * @param $sn    订单ID
     */
    public static  function sendGrant($uid, $event, $price = 0, $sn=''){
        // 1. 判断信息
        if( !isset( Grant::$EVENT_MAP[$event] ) ){
            return '事件不存在' . $event;
        }
        // 判断重复性
        if( RedisHelper::incr($uid.'_'.$event, 5) > 1 ){
            return '不能重复提交';
        }

        // 获取发放奖励
        $grantlist = Grant::getListByEvent($event);
        if( empty($grantlist) ){
            return '无赠送奖励';
        }

        // 用户注册
        if( $event == Grant::EVENT_REGIEST ){
            $eventGrantClass = new GrantRegist();
        }else if( $event == Grant::EVENT_LOGIN ){
            $eventGrantClass = new GrantLogin();
        }else if( $event == Grant::EVENT_IMPULSE){
            $eventGrantClass = new GrantImpulse();
        }else if( $event == Grant::EVENT_PAY_ONLINE){
            $eventGrantClass = new GrantPayOnline();
        }else if( $event == Grant::EVENT_PAY_BLANCE){
            $eventGrantClass = new GrantPayBlance();
        }else{
            return '事件类型还未实例化' . $event;
        }

        $eventGrantClass->sn = $sn;
        foreach ($grantlist as $row){
            // 判断是否满足条件
            if( $eventGrantClass->isFull($price, $row['condition']) == false){
                continue ;  # 不满足条件 进入下个循环
            }

            // 获取赠送价值 折扣还是比例
            $grant_value = $row['method'] == '1' ? $row['value'] : ceil($price * $row['value'] / 100);
            if($row['type'] == Grant::TYPE_CREADIT){
                $eventGrantClass->sendGold($uid, $grant_value);
            }
            if($row['type'] == Grant::TYPE_COUPON){
                $eventGrantClass->sendCoupon($uid, $row['value_id']);
            }
            if($row['type'] == Grant::TYPE_CASH){
                $eventGrantClass->sendCash($uid, $grant_value);
            }
        }
    }
}
?>