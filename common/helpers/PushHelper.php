<?php
namespace common\helpers;

/*
 * 推送消息服务层
 *
 * @author king <pengjp@bnersoft.com>
 * @date 2018年10月24日
 */

use common\models\Push\PushRecord;
use common\models\Push\PushUser;
use common\models\Push\Template;
use yii\redis\Cache;

class PushHelper
{
    const NONE	= 0;	// 不用推送
    const MOBILE = 1;	// 短信推送
    const APP	   = 2;	// APP推送
    const WEIXIN = 4;	// 微信公众号推送

    /**
     * 添加推送消息入口
     * @param $user_id   推送用户ID
     * @param $temp_code 推送模板ID
     * @param $param     模板替换参数
     * @param $send_way  推送方式 支持  短信、微信、APP
    */
    public static function addPush($user_id, $temp_code, $param = [], $model_way = 7){
        // 判断信息 模板是否存在
        $temp = Template::find()->where(['code' => $temp_code, 'status' => Template::STATUS_ON ])->one();
        if( empty($temp) ){
            return false;
        }

        $content = $temp->content;
        foreach ($param as $k => $val){
            $content = str_replace("{". ($k+1) ."}", $val, $content);
        }

       /* ($model_way & self::MOBILE) &&  PushRecord::addRow($user_id, $temp->sms_id, $temp_code, 'SMS', $param, $content);
        ($model_way & self::APP)    &&  PushRecord::addRow($user_id, $temp->sms_id, $temp_code, 'APP', $param, $content);
        ($model_way & self::WEIXIN) &&  PushRecord::addRow($user_id, $temp->sms_id, $temp_code, 'WX',  $param, $content);*/

         self::runPush(true); # 执行推送任务
    }

    /**
     * 执行推送任务
     * @param bool $isnew 是否是新任务
     */
    public static function runPush($isnew = false){
        $query = PushRecord::find()->with('user')->where(['return_code' => 0]);
        if($isnew == true){
            $query->andWhere(['send_count' => 0]);
        }else{
            $query->andWhere(['<', 'send_count', 3]);
        }
        $recordlist = $query->limit(1)->all();
        foreach ($recordlist as $record){
            $record->send_count += 1;
            if( empty($record->user) ){
                $record->return_code = '404';
                $record->return_msg = '用户信息不存在';
                $record->save();
                continue;   # 继续下个判断
            }

            $pushType = 'send' . $record->push_type;
            self::$pushType($record, $record->user);
        }
    }

    // 短信提醒
    public static function sendSMS($record, $user){
        $mobile = isset($user['mobile']) ? $user['mobile'] : '';
        if( empty($mobile) || strlen($mobile) != 11 ){
            $record->return_code = '401';
            $record->return_msg = '用户手机号格式错误';
            return $record->save();
        }
        if( empty($record->sms_id) ){
            $record->return_code = '401';
            $record->return_msg = '短信模板不可为空';
            return $record->save();
        }

        $record->return_code = '200';
        $record->return_msg = 'Success';
        return $record->save();

        $rest = new CCPRestSDK('app.cloopen.com', '8883', '2013-12-26');
        $rest->setAccount('aaf98f8953cadc690153cb4589f1281e', 'e2fab288f0db4a8ebaa6fa2d10a6fe0f');
        $rest->setAppId('aaf98f8953cadc690153cb4dea212838');
        $rest->setEnabeLog(true);

        $rest->setTempId($record->sms_id);
        $params = json_decode($record->msg_param, true);
        $result = $rest->sendTemplateSMS($mobile, $params);

        if ( !is_null($result) && $result->statusCode == 0) {
            $record->return_code = '200';
        }else{
            $record->return_code = '500';
        }
        $record->return_msg = json_encode($result, JSON_UNESCAPED_UNICODE);
        return $record->save();
    }

    // APP推送
    private static function sendApp($record, $user){
        if( strtolower($record->push_mimi) == 'ios' ){
            self::sendIOS($record, $user);
        }else if( strtolower($record->push_mimi) == 'android' ){
            self::sendAndroid($record, $user);
        }else{
            $record->return_code = '401';
            $record->return_msg = 'APP推送，设备类型错误';
            return $record->save();
        }
    }

    // 向Android机用户推送信息
    private static function sendAndroid($record, $user){
        $channel_id = isset($user['channid_and']) ? $user['channid_and'] : '';
        if( empty($channel_id) ){
            $record->return_code = '401';
            $record->return_msg = '渠道ID不存在';
            return $record->save();
        }

        $pusher =  self::createPushClient()->push()
            ->setPlatform(M\platform('android'))
            ->setAudience(M\registration_id([ $channel_id ]) )
            ->setNotification(M\notification($record->template_code, M\android($record->template_code) ) )
            ->setMessage(M\message($record->msg_content))
            ->setOptions(M\options(null, config('pusher.days') * 86400));

        list($code, $msg) = self::sendPush($pusher);
        $record->return_code = $code;
        $record->return_msg = $msg;
        return $record->save();
    }

    // 向IOS机用户推送信息
    private static function sendIOS($record, $user){
        $channel_id = isset($user['channid_ios']) ? $user['channid_ios'] : '';
        if( empty($channel_id) ){
            $record->return_code = '401';
            $record->return_msg = '渠道ID不存在';
            return $record->save();
        }

        $pusher =  self::createPushClient()->push()
            ->setPlatform(M\platform('ios'))
            ->setAudience(M\registration_id([ $channel_id ]) )
            ->setNotification(M\notification($record->template_code, M\ios($record->template_code, 'happy', 1, true, null, 'THE-CATEGORY') ) )
            ->setMessage(M\message($record->msg_content))
            ->setOptions(M\options(null, config('pusher.days') * 86400 , null, config('pusher.apns') ));

        list($code, $msg) = self::sendPush($pusher);
        $record->return_code = $code;
        $record->return_msg = $msg;
        return $record->save();
    }

    // 创建一个推送实体
    private static function createPushClient(){
        $app_key = config('pusher.app_key');
        $master_secret = config('pusher.master_secret');

        return new JPushClient($app_key, $master_secret);
    }

    // 推送信息 返回结果
    private static function sendPush($pusher){
        try {
            $pusher->send();
            return [200, 'App推送 success'];
        } catch (APIRequestException $e) {
            return [500, 'App推送 error'. $e->getMessage()];
        } catch (APIConnectionException $e) {
            return [500, 'App推送 error'. $e->getMessage()];
        }
    }

    // 向微信用户推送信息
    private static function sendWX($record, $user){
        $openid = isset($user['openid']) ? $user['openid'] : '';
        if( empty($openid) ){
            $record->return_code = '401';
            $record->return_msg = '微信OPENID不存在';
            return $record->save();
        }

       $postData = '{"touser": "'.$openid.'", "msgtype": "text", "text": {"content": "'.$record->msg_content.'"}}';
       $url = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=". self::getAccessToken();

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_TIMEOUT, 3);
        $results = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($results);
        if($result->errcode == 0){
            $record->return_code = '200';
        }else{
            $record->return_code = '500';
        }
        $record->return_msg = $results;
        return $record->save();
    }

    // 获取 access_token值 有时间限制【直接写缓存改为缓存机制】
    private static function getAccessToken(){
        if($tokenJson = RedisHelper::getCache('weixin_accesstoken') ){
            return $tokenJson;
        }

        $appID = 'wxb1b5e334f0fff2bd'; //config('pusher.appID');
        $appSecret = '4a70d2f0b541751aad9bf16f6ee082f7'; //config('pusher.appSecret');
        $fileUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appSecret;
        $tokenJson = json_decode( file_get_contents($fileUrl) ) ;
        if( empty($tokenJson) || isset($tokenJson->errcode) ){
            return false;
        }

        RedisHelper::setCache('weixin_accesstoken', $tokenJson->access_token,  $tokenJson->expires_in - 5 );
        return $tokenJson->access_token;
    }
    /*
    private static function getAccessToken(){
        $fileName = public_path("weixin/accesstoken.json");

        // 判断是否有文件
        if( is_file($fileName) ){
            $tokenJson = json_decode( file_get_contents($fileName) ) ;
            if( $tokenJson->expires_in > time() + 30 ){
                return $tokenJson->access_token;
            }
        }

        $appID = config('pusher.appID');
        $appSecret = config('pusher.appSecret');
        $fileUrl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=".$appID."&secret=".$appSecret;
        $tokenJson = json_decode( file_get_contents($fileUrl) ) ;
        if( empty($tokenJson) || isset($tokenJson->errcode) ){
            return false;
        }

        $tokenJson->expires_in += time();
        file_put_contents($fileName, json_encode($tokenJson));
        return $tokenJson->access_token;
    }*/
}
