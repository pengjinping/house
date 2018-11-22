<?php
namespace appapi\controllers;

use Yii;
use common\helpers\RedisHelper;
use common\helpers\AuthFilter;

use common\helpers\ValidateHelper;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use yii\data\ActiveDataProvider;

/**
 * Base controller
 */
class BaseController extends Controller
{
    public $checkAuth = true;       # 需要认证
    public $userinfo = [];           # 用户信息

    public function behaviors(){
        $behaviors = parent::behaviors();
        $behaviors['access'] = ['class' => AuthFilter::className() ];

        if( $this->checkAuth ){
            $this->setUserinfo();
        }

        return $behaviors;
    }

    // 判断用户是否登录
    protected function setUserinfo(){
        $request = Yii::$app->request;
        $authHeader =$request->headers->get('X-TOKEN');
        if( !$authHeader ){
            $authHeader = $request->getCookies()->get('auth-token');
        }
        if( !$authHeader ){
            $authHeader = $request->post('auth_token');
        }
        if( !$authHeader ){
            throw new BadRequestHttpException('缺少必要参数auth_token', 500);
        }

        RedisHelper::select(2);
        $userinfo = RedisHelper::getCache($authHeader);
        if( empty($userinfo) ){
            throw new UnauthorizedHttpException('还未登录，请先登录');
        }
        $this->userinfo = json_decode($userinfo, true);
    }

    // 获取用户传送token信息
    protected function getUserId(){
        if( empty($this->userinfo) || !$this->userinfo['id'] ){
            throw new UnauthorizedHttpException('还未登录，请先登录');
        }

        return $this->userinfo['id'];
    }

    // 对数据进行过滤校验
    protected function validata(&$inputs, $gules){
        if( $mes = ValidateHelper::make($inputs, $gules) ){
            throw new BadRequestHttpException($mes);
        }
    }

    // 对方法进行上锁
    protected function lock($userid='', $expire = 5){
        $path = Yii::$app->request->pathInfo . '_';
        $path .= $userid ? $userid : $this->getUserId();
        $rediskey = md5($path);
        if( RedisHelper::incr($rediskey, $expire) > 1 ){
            throw new BadRequestHttpException('请不要重复点击');
        }
        return $rediskey;
    }
    // 对方法进行解锁
    protected function unlock($rediskey = ''){
        if(!$rediskey){
            $path = Yii::$app->request->pathInfo . '_';
            $rediskey = md5($path  . $this->getUserId() );
        }
        RedisHelper::decr($rediskey);
    }

    // 数据分页功能
    protected function dataProvider($query, $sort=[]){
        $sort = empty($sort) ? ['id' => SORT_DESC] : $sort;
        return new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $_GET['pageSize'],
                'validatePage' => false
            ],
            'sort' => [
                'defaultOrder' => $sort
            ],
        ]);
    }
}
