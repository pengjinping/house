<?php
namespace common\helpers;

/*
 * 推送消息服务层
 *
 * @author king <pengjp@bnersoft.com>
 * @date 2018年10月24日
 */

use common\models\Member\Member;

class PushHelper
{
    const NONE	    = 0;	 // 不用推送
    const MOBILE  = 1;	    // 短信推送
    const WEIXIN	= 2;	// 微信公众号推送
    const APP      = 4;	// APP推送

    // 配置推送模板ID
    const TEMP_CODE_VCODE = 1;  // 发送验证码

    // 获取设备类型   [PC, WX, IOS, ANDROID，Other]
    public static function RequestSource(){
        $useragent = isset($_SERVER["HTTP_USER_AGENT"]) ? strtolower($_SERVER["HTTP_USER_AGENT"]) : '';
		if( $useragent == '' ){  return 'Other'; }

		// 微信
        if ( strripos($useragent, 'micromessenger') ) {
            return 'WX';
        }else if ( strripos($useragent, 'iphone') || strripos($useragent, 'ipad')
            || strripos($useragent, 'ipod') || strripos($useragent, 'cfnetwork') ) {
            return 'IOS';
        }else if (strripos($useragent, 'android')) {
            return 'ANDROID';
        }else if ( strripos($useragent, 'windows nt') ) {
            return 'PC';
        }else{
            return 'Other' . $useragent;
        }
    }

    /**
     * 推送消息入口
     * @param $user_id   用户ID
     * @param $temp_code 模板ID
     * @param $param     模板替换参数
     * @param $send_way  推送方式 支持  短信、微信、APP
     */
    public static function addPush($user_id, $temp_code, $param = [], $model_way = 7){
        $userinfo = Member::findOne($user_id);
        if( empty($userinfo) ){  return false; }

        ($model_way & self::MOBILE) &&  self::sendSMS($userinfo->mobile, $temp_code, $param);
        ($model_way & self::WEIXIN) &&  self::sendWX($userinfo->wx_openid, $temp_code, $param);
        ($model_way & self::APP)    &&  self::sendAPP($userinfo->channel, $temp_code, $param);
    }

    // 短信提醒
    public static function sendSMS($mobile, $temp_id, $param){
        if( empty($mobile) || strlen($mobile) != 11 ){
            return ['code' => 404, 'msg' => '手机号格式错误'];
        }

        $postdata['sendType'] = "SMS";
        $postdata['toid'] = $mobile;
        $postdata['tempId'] = $temp_id;
        $postdata['param'] = join(':', $param);

        return self::createCurl($postdata);
    }

    // 微信提醒
    public static function sendWX($openid, $temp_id, $param){
        if( empty($openid) ){
            return ['code' => 404, 'msg' => '微信OPENID不存在'];
        }
        $postdata['sendType'] = "WX";
        $postdata['toid'] = $openid;
        $postdata['tempId'] = $temp_id;
        $postdata['param'] = join(':', $param);

        return self::createCurl($postdata);
    }

    // APP提醒
    public static function sendAPP($channel, $temp_id, $param){
        if( empty($channel) ){
            return ['code' => 404, 'msg' => '极光推送渠道不存在'];
        }

        $postdata['sendType'] = self::RequestSource();
        $postdata['toid'] = $channel;
        $postdata['tempId'] = $temp_id;
        $postdata['param'] = join(':', $param);

        return self::createCurl($postdata);
    }

    private static function createCurl($postdata){
        $postdata['project'] = 'house';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'http://push.seohouse.top/push.php');
        curl_setopt($curl, CURLOPT_HEADER, 0);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postdata);
        $data = curl_exec($curl);
        curl_close($curl);

        return json_decode($data, true);
    }
}
