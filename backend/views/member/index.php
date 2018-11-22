<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member\Member;
use common\models\Member\Grade;

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="member-index col-sm-12" style="margin-top: 15px;">
    <div class="ibox ibox-content">
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            'id',
            ['attribute' => 'parent_id', "value" => function($model){
                return isset($model->leveldis) ? $model->leveldis->parent : '';
            } ],
            'username',
            'nick',
            'mobile',
            ['attribute' => 'sex', "value" => function($model){ return Member::$SEX_MAP[$model->sex]; } ],
            ['attribute' => 'headimg',  'format' => 'raw', "value" => function($model){
                if( $model->headimg ) return Html::img($model->headimg, ["width" => "44px"]);
            } ],
            ['attribute' => 'status', "value" => function($model){ return Member::$STATUS_MAP[$model->status]; } ],
            ['attribute' => 'grade_id', "value" => function($model){ return Grade::getCacheGrade($model->grade_id); } ],
            ['attribute' => 'created_at', "value" => function($model){ return date('Y-m-d H:i:s', $model->created_at); } ],
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'150px'],
                'template' => '{view} {update}',
                'buttons' => [
                    'view' => function($url, $model, $key){
                        return Html::a('查看', $url,['class' =>'btn btn-outline btn-default btn-xs']);
                    },
                    'update' => function($url, $model, $key){
                        return Html::a('修改', $url,['class' =>'btn btn-outline btn-info btn-xs']);
                    },
                ],
            ],
        ],
    ]); ?>
	</div>
</div>