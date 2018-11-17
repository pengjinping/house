<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use common\models\Member\Coupon;
use common\models\Member\MemberCoupon;

$this->title = '列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupon-index col-sm-12" style="margin-top: 15px;">
<div class="ibox ibox-content">
    <div class="coupon-search search-wrap">
        <?php $form = ActiveForm::begin([
            'action' => ['index-user'],
            'method' => 'get',
            'options' => ['class' => 'form-inline'],
            'fieldConfig' => [
                'options' => ['class' => 'form-group'],
                'template' => "{input}"
            ],
        ]); ?>

        <?= $form->field($searchModel, 'id')->textInput(['placeholder'=>'id'])?>
        <?= $form->field($searchModel, 'user_id')->textInput(['placeholder'=>'用户id'])?>
        <?= $form->field($searchModel, 'coupon_id')->textInput(['placeholder'=>'优惠券ID'])?>
        <?= $form->field($searchModel, 'pickup_scene')->textInput(['placeholder'=>'场景'])?>
        <?= $form->field($searchModel, 'status')->dropDownList(MemberCoupon::$STATUS_MAP, ['prompt' => '状态'])?>

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
            'coupon_id',
            ['attribute' => 'coupon_type', "value" => function($model){ return Coupon::$TYPE_MAP[$model->coupon_type]; } ],
            'coupon_full',
            'coupon_value',
            'expire_time',
            ['attribute' => 'status', "value" => function($model){ return MemberCoupon::$STATUS_MAP[$model->status]; } ],
            'pickup_scene',
            'created_at',
            'updated_at'
        ],
    ]); ?>
</div>
</div>