<?php
namespace appapi\controllers;


use common\models\Area;
use common\models\product\BuildHouse;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * Base controller
 */
class BuildController extends BaseController
{
    // 获取用户信息
    public function actionIndex(){

        $params = Yii::$app->request->post();
        $query = BuildHouse::find()->asArray();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $_GET['pageSize'],
                'validatePage' => false
            ],
            'sort' => [
                'defaultOrder' => [
                    'top'=> SORT_ASC,
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $query->select(["id", "title", "image", "area_id", "address", "developer", "type", "price", "flag", "rate"]);
        $query->andFilterWhere(['resource' => BuildHouse::RESOURCE_NEW]);
        $query->andFilterWhere(['status' => BuildHouse::STATUS_DISPLAY]);
        $query->andFilterWhere(['area_id' => @$params['area']]);
        $query->andFilterWhere(['type' => @$params['type']]);
        $query->andFilterWhere(['like', 'title', @$params['keyword']]);

        $list = $dataProvider->getModels();
        foreach ($list as &$item){
            $item['type'] = BuildHouse::$TYPE_MAP[$item['type']];
            $item['area'] = Area::getCacheArea(3, $item['area_id']);
        }

        return $list;
    }

    // 获取用户信息
    public function actionView(){

        $id = Yii::$app->request->post('id');
        $buildinfo = BuildHouse::findOne($id);
        if( $buildinfo == null ){
            return [];
        }

        $return = [];
        $return['id'] = (int)$buildinfo->id ;
        $return['title'] = $buildinfo->title ;
        $return['images'] = explode("\r\n", $buildinfo->images);
        $return['area'] = Area::getCacheArea(3, $buildinfo->area_id);
        $return['address'] = $buildinfo->address ;
        $return['developer'] = $buildinfo->developer ;
        $return['price'] = $buildinfo->price ;
        $return['type'] = BuildHouse::$TYPE_MAP[$buildinfo->type];
        $return['flag'] = $buildinfo->flag ?? '' ;
        $return['rate'] = $buildinfo->rate ;
        $return['content'] = $buildinfo->content->content ;

        return $return;
    }

    /**
     * 报备列表
     */
    public function actionComment(){
        $uid = $this->getUserId();
        $data = ['uid' => $uid];
        return $data;
    }

    /**
     * 报备功能 [过程上锁]
     */
    public function actionCommentSave(){
        $params = Yii::$app->request->post();
        $this->validata($params, array(
            'id' => 'required',
            'phone' => 'required|string|size:11',
            'username' => 'required|string',
        ));
        $this->lock();

        $data['id'] = $params['id'];
        $data['status'] = 1;

        $this->unlock();
        return $data;
    }

}
