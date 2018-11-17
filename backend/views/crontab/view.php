<?php

use yii\helpers\Html;
use common\models\Crontab\Crontab;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

</style>

<div class="crontab-view  wrapper wrapper-content animated fadeInRigh">
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
        	<tr><td>id</td><td><?=Html::encode($model->id);?></td></tr>
        	<tr><td>定时任务名称</td><td><?=Html::encode($model->name);?></td></tr>
        	<tr><td>任务路由</td><td><?=Html::encode($model->route);?></td></tr>
        	<tr><td>crontab格式</td><td><?=Html::encode($model->crontab_str);?></td></tr>
            <tr><td>任务描述</td><td><?=Html::encode($model->remarks);?></td></tr>
        	<tr><td>任务开关</td><td><?=Html::encode(Crontab::$switchTextMap[$model->switch]);?></td></tr>
        	<tr><td>上次任务运行时间</td><td><?=Html::encode($model->last_rundate);?></td></tr>
        	<tr><td>下次任务执行时间</td><td><?=Html::encode($model->next_rundate);?></td></tr>
        </tbody>
    </table>
	</div>
</div>
