<?php

use yii\helpers\Html;
use common\models\finance\FinanceLevel;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="finance-level-view  wrapper wrapper-content animated fadeInRigh">
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
            <tr><td>名称</td><td><?= Html::encode($model->title); ?></td></tr>
            <tr><td>最小值</td><td><?= Html::encode($model->min); ?></td></tr>
            <tr><td>最大值</td><td><?= Html::encode($model->max); ?></td></tr>
            <tr><td>渠道</td><td><?= Html::encode($model->channle); ?>%</td></tr>
            <tr><td>运营</td><td><?= Html::encode($model->operate); ?>%</td></tr>
            <tr><td>开单者</td><td><?= Html::encode($model->user); ?>%</td></tr>
            <tr><td>上级</td><td><?= Html::encode($model->parent); ?>%</td></tr>
            <tr><td>二级</td><td><?= Html::encode($model->grandpa); ?>%</td></tr>
            <tr><td>后台管理</td><td><?= Html::encode($model->admin); ?>%</td></tr>
            <tr><td>团建</td><td><?= Html::encode($model->league); ?>%</td></tr>
            <tr><td>团队管理</td><td><?= Html::encode($model->team); ?>%</td></tr>
            <tr><td>状态</td><td><?= Html::encode(FinanceLevel::$STATUS_MAP[$model->status]);?></td></tr>
        </tbody>
    </table>
	</div>
</div>
