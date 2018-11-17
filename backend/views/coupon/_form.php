<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use kartik\datetime\DateTimePicker;
use common\models\Member\Coupon;

?>

<div class="coupon-form">

    <?php $form = ActiveForm::begin([
    	'options' => ['class' => 'form-horizontal'],
    	'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
    	]
    ]); ?>

    <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'desc_txt')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'expire_days')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'total_count')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'limit')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'type')->dropDownList(Coupon::$TYPE_MAP) ?>

    <?= $form->field($model, 'full')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'value')->textInput(['maxlength' => true])->label('券值[折扣率]')?>

    <?= $form->field($model, 'product_type')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'product_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'get_method')->dropDownList(Coupon::$GET_METHOD_MAP) ?>

    <?= $form->field($model, 'status')->dropDownList(Coupon::$STATUS_MAP) ?>

    <?= $form->field($model, 'del_flag')->dropDownList(Coupon::$DEL_FLAG_MAP) ?>

    <?= $form->field($model, 'begin_time')->widget(DatePicker::classname(),
        ['options' => ['placeholder' => ''], 'pluginOptions' => ['format' => 'yyyy-mm-dd', 'todayHighlight' => true, 'autoclose' => true] ] ) ?>
    <?= $form->field($model, 'expire_time')->widget(DatePicker::classname(),
        ['options' => ['placeholder' => ''], 'pluginOptions' => ['format' => 'yyyy-mm-dd', 'todayHighlight' => true, 'autoclose' => true] ] )?>

    <div class="form-group">
    	<div class="col-sm-4 col-sm-offset-2">
        	<?= Html::submitButton($model->isNewRecord ? '创建' : '更新', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	</div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
