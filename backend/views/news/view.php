<?php

use yii\helpers\Html;
use common\models\product\BuildNews;

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="build-news-view  wrapper wrapper-content animated fadeInRigh">
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
		<tr><td>标题</td><td><?= Html::encode($model->title); ?></td></tr>
		<tr><td>原始URL</td><td><?= Html::encode($model->url); ?></td></tr>
        <tr><td>状态</td><td><?= Html::encode(BuildNews::$STATUS_MAP[$model->status]);?></td></tr>
		<tr><td>内容</td><td><?= Html::decode($model->content); ?></td></tr>
		<tr><td>入库时间</td><td><?= Html::encode($model->created_at); ?></td></tr>
        </tbody>
    </table>
	</div>
</div>
