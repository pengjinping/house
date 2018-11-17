<?php
namespace appapi\controllers;

use common\models\product\BuildNews;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * 最新资讯 controller
 */
class CommonController extends BaseController
{
    // ---------------------------  fav 收藏
    public function actionFav(){
        $uid = $this->getUserId();
        $data = ['uid' => $uid];
        return $data;
    }
    public function actionFavSave(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'id' => 'required',
            'status' => 'in:0,1',
            'type' => 'in:1,2,3',
        ));

        $this->lock();
        $data['id'] = $params['id'];
        $data['status'] = !$params['status'];

        $this->unlock();
        return $data;
    }

    // ---------------------------  watch 看房记录
    public function actionWatch(){
        $uid = $this->getUserId();
        $data = ['uid' => $uid];
        return $data;
    }
    public function actionWatchSave(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'name' => 'required',
            'phone' => 'required|mobile',
            'title' => 'required',
            'txt' => 'string',
        ));

        $this->lock();
        $data['id'] = $params['id'];
        $data['status'] = !$params['status'];

        $this->unlock();
        return 15;
    }

    // ---------------------------  customer 我的客服
    public function actionCustom(){
        $uid = $this->getUserId();
        $data = ['uid' => $uid];
        return $data;
    }
    public function actionCustomSave(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'name' => 'required',
            'phone' => 'required|mobile',
            'address' => 'required',
            'txt' => 'string',
        ));

        $this->lock();
        $data['id'] = $params['id'];
        $data['status'] = !$params['status'];

        $this->unlock();
        return 15;
    }




}
