<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\finance\FinanceRecord;

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="finance-record-index col-sm-12" style="margin-top: 15px;">
<div class="ibox ibox-content">
    <div class="finance-record-search search-wrap">
        <?php $form = ActiveForm::begin([
            'action' => ['reward'],
            'method' => 'get',
            'options' => ['class' => 'form-inline'],
            'fieldConfig' => [
                'options' => ['class' => 'form-group'],
                'template' => "{input}"
            ],
        ]); ?>

        <?= $form->field($searchModel, 'user_id')->textInput(['placeholder' => '用户ID']) ?>
        <?= $form->field($searchModel, 'record_id')->textInput(['placeholder' => '记录ID']) ?>
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
            'record_id',
            'level',
            'amount',
            'txt',
            'date',
            'created_at'
        ],
    ]); ?>
</div>
</div>