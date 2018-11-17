<?php

use yii\helpers\Html;
use common\models\product\BuildHouse;
use common\models\Area;
use yii\grid\GridView;

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="build-house-index col-sm-12" style="margin-top: 15px;">
    <p>
        <?= Html::a('创建', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <div class="ibox ibox-content">
        <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            'id',
            'title',
            ['attribute' => 'area_id', 'value' => function($model){ return Area::getCacheArea(3, $model->area_id); } ],
            'address',
            'developer',
            'price',
            ['attribute' => 'image',  'format' => 'raw', "value" => function($model){
                if( $model->image ) return Html::img($model->image, ["width" => "50px"]);
            }],
            'rate',
            'leader',
            'lead_phone',
            ['attribute' => 'type', 'value' => function($model){ return BuildHouse::$TYPE_MAP[$model->type]; } ],
            ['attribute' => 'status', 'value' => function($model){ return BuildHouse::$STATUS_MAP[$model->status]; } ],
            'top',
            'created_at',
            'updated_at',
            /* [ 'attribute' => 'sort', 'value' => function($model){ return $model->name; } ],
            [
                'headerOptions' => ['width'=>'100px'],
                'attribute' => 'image',
                'class' => zh\images\ViewImages::className(),
            ],*/
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'150px'],
                'template' => '{view} {update} {delete}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('查看', $url,['class' =>'btn btn-outline btn-default btn-xs']);
                    },
                    'update' => function($url, $model, $key){
                    	return Html::a('修改', $url,['class' =>'btn btn-outline btn-info btn-xs']);
                   	},
                    'delete' => function($url, $model, $key){
                       	return Html::a('删除', $url,['data-confirm' => '你确定要删除吗?','data-method' => 'POST','class' =>'btn btn-outline btn-danger btn-xs']);
                   	},
               ],
            ],
        ],
    ]); ?>
	</div>
</div>