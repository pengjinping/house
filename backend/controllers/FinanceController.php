<?php

namespace backend\controllers;

use common\models\finance\FinanceLevel;
use common\models\finance\FinanceRecord;
use common\models\Member\MemberCoupon;
use Yii;
use backend\search\FinanceRecordSearch;
use yii\web\Controller;
use yii\filters\VerbFilter;

class FinanceController extends Controller
{

    /**
     * Lists all FinanceRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FinanceRecordSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/finance-level/record', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new FinanceLevel model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new FinanceRecord();
        if ($model->load(Yii::$app->request->post()) ) {

            if($level = FinanceLevel::findByPrice($model->price)){
                $model->level_id = $level['id'];
                $model->channle = ($model->price * $level['channle']) / 100;
                $model->operate = ($model->price * $level['operate']) / 100;
                $model->user = ($model->price * $level['user']) / 100;
                $model->parent = ($model->price * $level['parent']) / 100;
                $model->grandpa = ($model->price * $level['grandpa']) / 100;
                $model->league = ($model->price * $level['league']) / 100;
                $model->team = ($model->price * $level['team']) / 100;
            }

            // 判断用户信息 [分销配置]
            //$userInfo = Member....
            $userInfos[2] = ['user_id' => 2, 'user_level' => 2, 'parent' => 1, 'grandpa' => 0, 'teamnum' => 0, 'teamids' => '',];
            $userInfos[3] = ['user_id' => 3, 'user_level' => 3, 'parent' => 2, 'grandpa' => 1, 'teamnum' => 0, 'teamids' => '',];
            $userInfos[4] = ['user_id' => 4, 'user_level' => 4, 'parent' => 3, 'grandpa' => 2, 'teamnum' => 1, 'teamids' => '1',];
            $userInfos[5] = ['user_id' => 5, 'user_level' => 5, 'parent' => 4, 'grandpa' => 3, 'teamnum' => 2, 'teamids' => '2,1',];
            $userInfos[6] = ['user_id' => 6, 'user_level' => 4, 'parent' => 3, 'grandpa' => 2, 'teamnum' => 1, 'teamids' => '1',];

            $userInfo = $userInfos[$model->user_id];
            // 第二级用户 福利
            if($userInfo['grandpa'] == 0){
                $model->user += $model->grandpa;
                $model->grandpa = 0;
            }

            // 用户含有多少个管理者
            $model->team *= $userInfo['teamnum'];
            $model->league -= $model->team;
            $model->created_at = date('Y-m-d H:i:s');
            $model->save();
            return $this->redirect(['/finance/index']);
        }

        return $this->render('/finance-level/record_create', [
            'model' => $model,
        ]);
    }
}
