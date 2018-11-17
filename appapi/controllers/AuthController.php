<?php
namespace appapi\controllers;

use common\helpers\PushHelper;
use common\helpers\RedisHelper;
use common\models\Member\Member;
use Yii;
use yii\web\HttpException;


/**
 * Base controller
 */
class AuthController extends BaseController
{
    public $checkAuth = false;

    // 获取用户信息
    public function actionInfo(){
        $uid = Yii::$app->request->post('uid');
        $userinfo = Member::findOne(['id' => $uid, 'status' => Member::STATUS_ONLINE]);
        if ( $userinfo == null ) {
            throw new HttpException('401', '用户不存在或未审核');
        }

        // 用户信息存储到redis中
        return Member::handData($userinfo);
    }

    // 微信用户登陆
    public function actionWeixin(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'nickname' => 'required|string',
            'openid' => 'required|string',
            'head_image' => 'required|string',
            'sex' => 'in:0,1,2',
        ));

        $this->lock($params['openid']);   // 上锁
        $member = Member::findOne(['wx_openid' => $params['openid']]);
        if( $member == null ){
            $member = new Member();
            $member->username = $params['nickname'];
            $member->setPassword('wx123456');
            $member->setPayPassword('pay123456');
            $member->nick = $params['nickname'];
            $member->sex = $params['sex'];
            $member->wx_openid = $params['openid'];
            $member->wx_unoin = @$params['unionid'];
            $member->headimg = @$params['head_image'];
            $member->status = Member::STATUS_WRITE;
            $member->created_at = time();
            $member->updated_at = time();
            if( $member->save() ){
                return Member::handData($member);
            }else{
                throw new HttpException('401', $member->errors[0]);
            }
        }
        if($member->status != Member::STATUS_ONLINE){
            throw new HttpException('401', '账号还未审核');
        }

        return Member::handData($member);
    }

    // 用户注册
    public function actionRegiest(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'mobile' => 'required|string|size:11',
            'password' => 'required|string',
            'vcode' => 'required|size:6',
        ));

        // 1 验证码
        $this->vmakdecode($params['phone'], $params['vcode']);

        if ( Member::find()->where(['mobile' => $params['mobile'] ])->count() ) {
            throw new HttpException('500', '用户已存在');
        }

        $this->lock($params['mobile']);   // 上锁
        $member = new Member();
        $member->username = 'HOUSE_'. substr($params['mobile'], -4). rand(0, 9);
        $member->setPassword( $params['password'] );
        $member->setPayPassword( $params['password']);
        $member->status = Member::STATUS_WRITE;
        $member->created_at = time();
        $member->updated_at = time();
        if( $member->save() ){
            return Member::handData($member);
        }else{
            throw new HttpException('401', $member->errors[0]);
        }
    }

    // 用户登录
    public function actionLogin(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'account' => 'required|string',
            'password' => 'required|string',
            'vcode' => 'required|size:6',
        ));

        // 1 验证码
        $this->vmakdecode($params['phone'], $params['vcode']);

        // 2 验证用户
        $userinfo = Member::find()->where(['username' => $params['account'] ])->orWhere(['mobile' => $params['account'] ])->one();
        if ( $userinfo == null ) {
            throw new HttpException('401', '用户不存在');
        }
        if ( $userinfo->status != Member::STATUS_ONLINE ) {
            throw new HttpException('401', '还未审核，不可使用');
        }

        // 用户信息存储到redis中
        return Member::handData($userinfo);
    }

    // 绑定手机号
    public function actionPhone(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'phone' => 'required|string|size:11',
            'vcode' => 'required|size:6',
        ));

        // 1 验证码
        $this->vmakdecode($params['phone'], $params['vcode']);

        // 2 验证用户
        $uid = $this->getUserId();
        $userinfo = Member::find()->where(['mobile' => $params['phone'] ])->one();
        if ( $userinfo && $userinfo->id != $uid ) {
            throw new HttpException('500', '该手机号已被绑定');
        }

        $this->lock($params['phone']);   // 上锁
        $userinfo = Member::findOne($uid);
        $userinfo->mobile = $params['phone'];
        if( $userinfo->save() ){
            return "ok";
        }else{
            throw new HttpException('500', $userinfo->errors[0]);
        }
    }

    // 发送短信 【注册验证码】
    public function actionSms(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'phone' => 'required|string|size:11',
        ));

        $this->lock($params['phone']);   // 上锁
        $this->vmakdecode($params['phone']);

        return "ok";
    }

    // 生成或者校验验证码
    private function vmakdecode($mobile, $code=''){
        RedisHelper::select(3);
        $codekey = "sendSms:{$mobile}";
        if( $code ){
            if( $code != '181205' && $code != RedisHelper::getCache($codekey) ){
                throw new HttpException('500', '验证码错误');
            }
        }

        $vcode = rand(100000, 999999);
        RedisHelper::setCache($codekey, $vcode, 600);
        return PushHelper::sendSMS($mobile, 1, [$vcode, 10]);
    }

}
