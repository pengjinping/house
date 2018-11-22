<?php

use yii\helpers\Html;
use common\models\Member\Member;
use common\models\Member\Grade;

$this->title = $model->username;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="build-news-view  wrapper wrapper-content animated fadeInRigh">
	<div class="ibox-content col-sm-8 col-sm-offset-2 page">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('修改', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
    </p>
    <table style="text-align:left" class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align:center" colspan="3"><h1>详情查看</h1></th>
            </tr>
        </thead>
        <tbody>

		<tr><td>id</td><td><?= Html::encode($model->id); ?></td></tr>
		<tr><td>用户名</td><td><?= Html::encode($model->username); ?></td></tr>
        <tr><td>昵称</td><td><?= Html::encode($model->nick); ?></td></tr>
		<tr><td>手机号</td><td><?= Html::encode($model->mobile); ?></td></tr>
        <tr><td>性别</td><td><?= Html::encode(Member::$SEX_MAP[$model->sex]);?></td></tr>
		<tr><td>上级</td><td><?= Html::decode($parents); ?></td></tr>
        <tr><td>级别</td><td><?= Html::decode(Grade::getCacheGrade($model->grade_id) ); ?></td></tr>
        <tr><td>状态</td><td><?= Html::encode(Member::$STATUS_MAP[$model->status]);?></td></tr>
		<tr><td>注册时间</td><td><?= date('Y-m-d H:i:s', $model->created_at); ?></td></tr>
        </tbody>
    </table>
	</div>
</div>
