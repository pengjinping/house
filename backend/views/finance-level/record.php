<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\finance\FinanceRecord;

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="finance-record-index col-sm-12" style="margin-top: 15px;">
    <p>
        <?= Html::a('添加记录', ['/finance/create'], ['class' => 'btn btn-success']) ?>
    </p>
<div class="ibox ibox-content">
    <div class="finance-record-search search-wrap">
        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            'options' => ['class' => 'form-inline'],
            'fieldConfig' => [
                'options' => ['class' => 'form-group'],
                'template' => "{input}"
            ],
        ]); ?>

        <?= $form->field($searchModel, 'title')->textInput(['placeholder' => '名称']) ?>
        <?= $form->field($searchModel, 'user_id')->textInput(['placeholder' => '用户ID']) ?>
        <?= $form->field($searchModel, 'price')->textInput(['placeholder' => '开单金额']) ?>
        <?= $form->field($searchModel, 'status')->dropDownList(FinanceRecord::$STATUS_MAP, ['prompt' => '状态']) ?>
        <?= $form->field($searchModel, 'date')->textInput(['placeholder' => '开单时间']) ?>

        <div class="form-group">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
    <hr />

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'tableOptions' => ['class' => 'table table-striped'],
        'columns' => [
            'id',
            'user_id',
            'title',
            'price',
            'level_id',
            'channle',
            'operate',
            'user',
            'parent',
            'grandpa',
            'admin',
            'team',
            'league',
            'date',
            ['attribute' => 'status', 'value' => function($model){ return FinanceRecord::$STATUS_MAP[$model->status]; } ],
            'created_at',
            [
                'class' => 'yii\grid\ActionColumn',
                'header' => '操作',
                'headerOptions' => ['width'=>'50px'],
                'template' => '{update}',
                'buttons' => [
                    'update' => function($url, $model){
                        if($model->status == FinanceRecord::STATUS_OFF){
                            return Html::a('修改', $url,['class' =>'btn btn-outline btn-info btn-xs']);
                        }
                    },
                ],
            ],
        ],
    ]); ?>
</div>
</div>