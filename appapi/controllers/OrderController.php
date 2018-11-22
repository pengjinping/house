<?php
namespace appapi\controllers;

use common\models\finance\FinanceRecord;
use common\models\finance\FinanceReward;
use common\models\product\BuildNews;
use Yii;


class OrderController extends BaseController
{
    // 我的订单
    public function actionList(){
        $uid = $this->getUserId();

        $query = FinanceRecord::find()->where(['user_id' => $uid])
            ->select(["id", "date", "price", "title"])
            ->asArray();
        $dataProvider = $this->dataProvider($query);

        $list = $dataProvider->getModels();
        return $list;
    }

    // 我的工资
    public function actionReward(){
        $uid = $this->getUserId();

        $page = empty($_GET['page']) ? 3 : $_GET['page']*3;
        $date = date('Y-m-01', strtotime("-{$page} months"));

        $list = FinanceReward::find()->where(['user_id' => $uid])->andWhere(['>', 'date', $date])->asArray()->all();
        $listdata = [];
        foreach ($list as $row){
            $datekey = substr($row['date'], 0, 7);
            if( isset($listdata[$datekey]) ){
                $listdata[$datekey]['date'] = $datekey;
                $listdata[$datekey]['price'] += $row['amount'];
                $listdata[$datekey]['list'][] = $row;
            }else{
                $listdata[$datekey] = [];
                $listdata[$datekey]['date'] = $datekey;
                $listdata[$datekey]['price'] = $row['amount'];
                $listdata[$datekey]['list'][] = $row;
            }
        }
        sort($listdata);
        return $listdata;
    }
}
