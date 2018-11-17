<?php

use yii\helpers\Html;
use common\models\product\BuildHouse;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="build-house-view  wrapper wrapper-content animated fadeInRigh">
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

		<tr><td>名称</td><td><?= Html::encode($model->title); ?></td></tr>
        <tr><td>区域</td><td><?= Html::encode(\common\models\Area::getCacheArea(3, $model->area_id));?></td></tr>
        <tr><td>地址</td><td><?= Html::encode($model->address); ?></td></tr>
        <tr><td>开发商</td><td><?= Html::encode($model->developer); ?></td></tr>
        <tr><td>类型</td><td><?= Html::encode(BuildHouse::$TYPE_MAP[$model->type]);?></td></tr>
        <tr><td>单价</td><td><?= Html::encode($model->price); ?> /平米</td></tr>
        <tr><td>佣金</td><td><?= Html::encode($model->rate); ?>%</td></tr>
        <tr><td>负责人</td><td><?= Html::encode($model->leader); ?></td></tr>
        <tr><td>负责人电话</td><td><?= Html::encode($model->lead_phone); ?></td></tr>
        <tr><td>驻场人</td><td><?= Html::encode($model->stater); ?></td></tr>
        <tr><td>驻场电话</td><td><?= Html::encode($model->state_phone); ?></td></tr>
		<tr><td>缩略图</td><td><?= Html::img($model->image, ["width" => "50px"]); ?></td></tr>
		<tr><td>简介图</td><td><?php
            foreach ($model->imageses as $image){
                echo Html::img($image, ["width" => "100px"]);
                echo " &nbsp;";
            }?></td></tr>

		<tr><td>排序</td><td><?= Html::encode($model->top); ?></td></tr>
		<tr><td>状态</td><td><?= Html::encode(BuildHouse::$STATUS_MAP[$model->status]);?></td></tr>
		<tr><td>发布时间</td><td><?= Html::encode($model->created_at); ?></td></tr>
		<tr><td>更新时间</td><td><?= Html::encode($model->updated_at); ?></td></tr>
        <tr><td>内容</td><td><?= Html::decode($model->content?$model->content->content:'' ); ?></td></tr>
        </tbody>
    </table>
	</div>
</div>
