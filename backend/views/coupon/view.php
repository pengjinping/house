<?php

use yii\helpers\Html;
use common\models\Member\Coupon;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="coupon-view  wrapper wrapper-content animated fadeInRigh">
	<div class="ibox-content col-sm-8 col-sm-offset-2 page">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('删除', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => '你确定要删除?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <table style="text-align:left" class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align:center" colspan="3"><h1>详情查看</h1></th>
            </tr>
        </thead>
        <tbody>
            <tr><td>id</td><td><?= Html::encode($model->id); ?></td></tr>
            <tr><td>优惠券名称</td><td><?= Html::encode($model->name); ?></td></tr>
            <tr><td>备注描述</td><td><?= Html::encode($model->desc_txt); ?></td></tr>
            <tr><td>领取后多少天内失效</td><td><?= Html::encode($model->expire_days); ?></td></tr>
            <tr><td>最高发行多少张券</td><td><?= Html::encode($model->total_count); ?></td></tr>
            <tr><td>已经领取了多少张</td><td><?= Html::encode($model->pick_count); ?></td></tr>
            <tr><td>每个人最多领多少张券</td><td><?= Html::encode($model->limit); ?></td></tr>
            <tr><td>优惠券类型</td><td><?= Html::encode(Coupon::$TYPE_MAP[$model->type]);?></td></tr>
            <tr><td>满多少可用</td><td><?= Html::encode($model->full); ?></td></tr>
            <tr><td>券值[折扣率]</td><td><?= Html::encode($model->value); ?></td></tr>
            <tr><td>适用的类型</td><td><?= Html::encode($model->product_type); ?></td></tr>
            <tr><td>适用的产品id</td><td><?= Html::encode($model->product_id); ?></td></tr>
            <tr><td>获取方法</td><td><?= Html::encode(Coupon::$GET_METHOD_MAP[$model->get_method]);?></td></tr>
            <tr><td>优惠码</td><td><?= Html::encode($model->code); ?></td></tr>
            <tr><td>删除标记</td><td><?= Html::encode(Coupon::$DEL_FLAG_MAP[$model->del_flag]);?></td></tr>
            <tr><td>状态</td><td><?= Html::encode(Coupon::$STATUS_MAP[$model->status]);?></td></tr>
            <tr><td>开始时间</td><td><?= Html::encode($model->begin_time); ?></td></tr>
            <tr><td>过期时间</td><td><?= Html::encode($model->expire_time); ?></td></tr>
            <tr><td>创建时间</td><td><?= Html::encode($model->created_at); ?></td></tr>
            <tr><td>更新时间</td><td><?= Html::encode($model->updated_at); ?></td></tr>
        </tbody>
    </table>
	</div>
</div>
