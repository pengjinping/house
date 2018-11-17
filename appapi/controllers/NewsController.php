<?php
namespace appapi\controllers;

use common\models\product\BuildNews;
use Yii;
use yii\data\ActiveDataProvider;

/**
 * 最新资讯 controller
 */
class NewsController extends BaseController
{
    // 获取用户信息
    public function actionIndex(){

        $params = Yii::$app->request->post();
        $query = BuildNews::find()->asArray();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $_GET['pageSize'],
                'validatePage' => false
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);

        $query->select(["id", "title"]);
        $query->andFilterWhere(['status' => BuildNews::STATUS_ON]);
        $query->andFilterWhere(['like', 'title', @$params['keyword']]);

        $list = $dataProvider->getModels();
        return $list;
    }

    // 获取用户信息
    public function actionView(){

        $id = Yii::$app->request->post('id');

        $info = BuildNews::findOne($id);
        if( $info == null ){  return []; }

        $return = [];
        $return['id'] = (int)$info->id ;
        $return['title'] = $info->title ;
        $return['content'] = $info->content;
        $return['datetime'] = $info->created_at;

        return $return;
    }

}
