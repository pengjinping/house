<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\finance\FinanceLevel;

?>

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

    <?= $form->field($model, 'min')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'max')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'channle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'operate')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'user')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'parent')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'grandpa')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'admin')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'league')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'team')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
    	<div class="col-sm-4 col-sm-offset-2">
        	<?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
