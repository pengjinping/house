<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\finance\FinanceRecord;

$this->title = '修改';
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '更新';
?>

<div class="finance-level-update col-sm-12">
	<div class=" ibox ibox-content" style="margin-top: 15px;">
        <div class="finance-level-form">

            <?php $form = ActiveForm::begin([
                'options' => ['class' => 'form-horizontal'],
                'fieldConfig' => [
                    'options' => ['class' => 'form-group'],
                    'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
                    'labelOptions' => ['class' => 'col-sm-2 control-label'],
                ]
            ]); ?>

            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'channle')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'operate')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'parent')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'grandpa')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'admin')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'league')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'team')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'league')->textInput(['maxlength' => true]) ?>

            <?= $form->field($model, 'status')->dropDownList(FinanceRecord::$STATUS_MAP) ?>

            <div class="form-group">
                <div class="col-sm-4 col-sm-offset-2">
                    <?= Html::submitButton('更新', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
	</div>
</div>