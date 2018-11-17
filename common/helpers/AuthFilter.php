<?php
namespace common\helpers;

use Yii;
use yii\base\ActionFilter;
use yii\web\HttpException;

/*
 * URL 签名验证
 * test 为日期时 可以不进行签名认证
 */
class AuthFilter extends ActionFilter
{
    // URL白名单 无需签名
    private $noNeeds = [
        'app/init',
        'auth/login',
        'auth/weixin'
    ];

    public function beforeAction($action)
    {
        $pathinfo = Yii::$app->request->pathInfo;
        $paths = explode('/', $pathinfo);
        if( in_array($pathinfo, $this->noNeeds) || in_array($paths[0].'/*', $this->noNeeds) ){
            return parent::beforeAction($action);
        }

        // 对参数进行处理 POST 转GET 与Header
        $_GET['page'] = @Yii::$app->request->post('page') ?? 1;
        $_GET['pageSize'] = @Yii::$app->request->post('pageSize') ?? 5;

        if( Yii::$app->request->get('test', '') != date('md') ){
            $nonce = Yii::$app->request->get('nonce', '');
            $timestamp = Yii::$app->request->get('timestamp', 0);
            $sign = Yii::$app->request->get('signature', '');

            $this->checkRequire($nonce, $timestamp, $sign);
            $this->checkExpire($timestamp);
            if( !$this->isDev($nonce, $timestamp, $sign) ){
                $this->checkSign($nonce, $timestamp, $sign);
            }
        }
        return parent::beforeAction($action);
    }

    // 校验签名是否存在
    private function checkRequire($nonce, $timestamp, $sign){
        if(!$timestamp || !$sign || !$nonce){
            throw new HttpException(403, 'URL需要签名');
        }
    }
    // 校验签名是否是测试验证
    private function isDev($nonce, $timestamp, $sign){
        return $nonce == 1 && $timestamp == 1 && $sign == 1;
    }

    // 校验签名是否合法
    private function checkSign($nonce, $timestamp, $sign){
        $md5key = "$nonce:$timestamp:" . md5('education');
        if( md5($md5key) != $sign ){
            throw new HttpException(403, 'URL签名失败');
        }
    }

    // 校验URL过期
    private function checkExpire($timestamp, $expire=300){
        if( $timestamp + $expire < time() ){
            throw new HttpException(403, 'URL已经过期');
        }
    }

}
