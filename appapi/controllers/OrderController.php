<?php
namespace appapi\controllers;

use common\models\product\BuildNews;
use Yii;
use yii\data\ActiveDataProvider;

class OrderController extends BaseController
{
    // 我的订单
    public function actionIndex(){
        $uid = $this->getUserId();
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
        $list = $dataProvider->getModels();
        return $list;
    }

    // 我的工资
    public function actionReward(){
        $uid = $this->getUserId();
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
        $list = $dataProvider->getModels();
        return $list;
    }
}
