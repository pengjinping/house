<?php

namespace backend\controllers;

use common\models\finance\FinanceLevel;
use common\models\finance\FinanceRecord;
use common\models\finance\FinanceReward;
use common\models\Member\MemberLevel;
use Yii;
use backend\search\FinanceRecordSearch;
use backend\search\FinanceRewardSearch;
use yii\web\BadRequestHttpException;
use yii\web\Controller;


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
     * Lists all FinanceRecord models.
     * @return mixed
     */
    public function actionReward()
    {
        $searchModel = new FinanceRewardSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('/finance-level/reward', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Updates an existing FinanceLevel model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = FinanceRecord::findOne($id);
        if ( $model == null) {
            throw new NotFoundHttpException('数据不存在');
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if($model->status == FinanceRecord::STATUS_ON){
                FinanceReward::addOneSave($model);
            }

            return $this->redirect(['index']);
        }

        return $this->render('/finance-level/record_update', [
            'model' => $model,
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
                $model->admin = ($model->price * $level['admin']) / 100;
                $model->league = ($model->price * $level['league']) / 100;
                $model->team = ($model->price * $level['team']) / 100;
            }

            $userInfo = MemberLevel::findOne(['user_id' => $model->user_id]);
            if($userInfo == null){
                throw new BadRequestHttpException('用户还没有分级，快去设置一下吧');
            }

            // 第一级用户 福利
            if($userInfo['parent'] == 0){
                $model->user += $model->parent;
                $model->parent = 0;
            }

            // 第二级用户 福利
            if($userInfo['grandpa'] == 0){
                $model->user += $model->grandpa;
                $model->grandpa = 0;
            }

            // 管理费用
            $teamnum = explode(',', $userInfo['teamids']);
            $model->team *= count($teamnum);
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
