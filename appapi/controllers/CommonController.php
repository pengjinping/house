<?php
namespace appapi\controllers;

use common\models\Member\MemberFav;
use common\models\product\BuildHouse;
use common\services\BuildHouseService;
use Yii;


/**
 * 最新资讯 controller
 */
class CommonController extends BaseController
{
    // ---------------------------  fav 收藏
    public function actionFav(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            't' => 'in:1,2,3',
        ));

        $uid = $this->getUserId();
        $valueids = MemberFav::find()->where(['user_id' => $uid])->andWhere(['type' => $params['t']])->select('value')->column();
        $query = BuildHouse::find()->asArray();
        $query->andWhere(['id' => $valueids]);
        $dataProvider = $this->dataProvider($query,  ['top'=> SORT_ASC, 'id' => SORT_DESC]);

        return BuildHouseService::handData($dataProvider, $query, $params);
    }
    public function actionFavSave(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'id' => 'required',
            'status' => 'in:0,1',
            'type' => 'in:1,2,3',
        ));

        $this->lock();
        $uid = $this->getUserId();
        if($params['status'] == 1){
            $fav = new MemberFav();
            $fav->user_id = $uid;
            $fav->type = $params['type'];
            $fav->value = $params['id'];
            $fav->created_at = date('Y-m-d H:i:s');
            $fav->save();
        }else{
            $where['user_id'] = $uid;
            $where['value'] = $params['id'];
            $where['type'] = $params['type'];
            $fav = MemberFav::find()->where($where)->one();
            $fav->delete();
        }
        $this->unlock();

        $data['id'] = $fav->id;
        $data['status'] = !$params['status'];
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
