<?php

namespace backend\controllers;

use common\models\product\BuildContent;
use Yii;
use common\models\product\BuildHouse;
use backend\search\product\BuildSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class BuildController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
                'config' => [
                    "imagePathFormat" => "/image/{yyyy}{mm}{dd}/{time}{rand:6}", //上传保存路径
                ]
            ]
        ];
    }

    /**
     * 新房源
     */
    public function actionIndex()
    {
        $searchModel = new BuildSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, BuildHouse::RESOURCE_NEW);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays BuildHouse model.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BuildHouse model.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new BuildHouse();
        $modelContent = new BuildContent();

        if ( $model->load(Yii::$app->request->post()) && $modelContent->load(Yii::$app->request->post()) ) {
            $model->resource = BuildHouse::RESOURCE_NEW;
            $model->created_at = date('Y-m-d H:i:s');
            $model->updated_at = date('Y-m-d H:i:s');
            if( $model->save() ){
                $modelContent->build_id = $model->id;
                $modelContent->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
        return $this->render('create', [
            'model' => $model,
            'modelContent' => $modelContent
        ]);
    }

    /**
     * Updates an existing BuildHouse model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $modelContent = BuildContent::findOne(['build_id' => $id]);
        if($modelContent == null){
            $modelContent = new BuildContent();
        }

        if ( $model->load(Yii::$app->request->post()) && $modelContent->load(Yii::$app->request->post()) ) {
            $model->updated_at = date('Y-m-d H:i:s');
            if( $model->save() ){
                $modelContent->build_id = $model->id;
                $modelContent->save();
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'modelContent' => $modelContent
        ]);
    }

    /**
     * Deletes an existing BuildHouse model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException | \Throwable if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        $modelContent = BuildContent::findOne(['build_id' => $id]);
        if($modelContent == null){
            $modelContent = new BuildContent();
        }
        $modelContent->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the BuildHouse model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BuildHouse the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BuildHouse::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('数据不存在');
    }
}
