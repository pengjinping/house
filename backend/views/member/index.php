<?php

use yii\helpers\Html;
use yii\grid\GridView;
use common\models\Member\Member;

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
            'username',
            'nick',
            'idcard',
            'realname',
            'mobile',
            ['attribute' => 'sex', "value" => function($model){ return Member::$SEX_MAP[$model->sex]; } ],
            'wx_openid',
            ['attribute' => 'headimg',  'format' => 'raw', "value" => function($model){
                if( $model->headimg ) return Html::img($model->headimg, ["width" => "44px"]);
            } ],
            ['attribute' => 'status', "value" => function($model){ return Member::$STATUS_MAP[$model->status]; } ],
            ['attribute' => 'created_at', "value" => function($model){ return date('Y-m-d H:i:s', $model->created_at); } ],
        ],
    ]); ?>
	</div>
</div>