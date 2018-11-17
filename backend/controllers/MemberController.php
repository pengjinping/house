<?php

namespace backend\controllers;

use Yii;
use backend\search\member\MemberSearch;
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
}
