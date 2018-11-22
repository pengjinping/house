<?php

namespace backend\controllers;

use common\models\Member\MemberLevel;
use Yii;
use backend\search\member\MemberSearch;
use common\models\Member\Member;
use yii\web\Controller;


class MemberController extends Controller
{
    /**
     * Lists all Member models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new MemberSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays BuildNews model.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $userinfo = $this->findModel($id);
        $parents = $userinfo->getParentsName();

        return $this->render('view', [
            'model' => $userinfo,
            'parents' => join(' -> ', $parents)
        ]);
    }

    /**
     * Updates an existing BuildNews model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $model->parent_id = isset($model->leveldis) ? $model->leveldis->parent : '';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $parent_id = Yii::$app->request->post('Member')['parent_id'];
            if($parent_id != $model->parent_id){
                MemberLevel::addOneSave($model->id, $parent_id);
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Finds the BuildNews model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildNews the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Member::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(数据不存在);
    }
}
