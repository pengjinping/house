<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Member\Member;
use common\models\Member\Grade;

$this->title = '修改';
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->username, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="build-news-update col-sm-12">
	<div class=" ibox ibox-content" style="margin-top: 15px;">
        <div class="build-news-form">

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group'],
                    'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ]
            ]); ?>

            <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList(Member::$STATUS_MAP) ?>
            <?= $form->field($model, 'grade_id')->dropDownList(Grade::getCacheGrade()) ?>
            <?= $form->field($model, 'parent_id')->textInput(['maxlength' => true])->label('上级用户ID') ?>


            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?= Html::submitButton( '更新', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>

        </div>
	</div>
</div>