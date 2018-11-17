<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel common\search\AccountService */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="area-index col-sm-12" style="margin-top: 15px;">
    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
<div class="ibox ibox-content">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        //'filterModel' => $searchModel,
        'columns' => [
            'code',
            'name',
            ['attribute' => 'level', "value" => function($model){ return \common\models\Area::$LEVEL_MAP[$model->level]; } ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'150px'],
                'template' => '{update} {delete}',
                'buttons' => [
                    'update' => function($url){
                    	return Html::a('修改', $url, ['class' =>'btn btn-outline btn-info btn-xs']);
                   	},
                    'delete' => function($url){
                       	return Html::a('删除', $url, ['data-confirm' => '你确定要删除吗?','data-method' => 'POST','class' =>'btn btn-outline btn-danger btn-xs']);
                   	},
               ],
            ],
        ],
    ]); ?>
	</div>
</div>