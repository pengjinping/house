<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Member\Coupon;

?>

<div class="coupon-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder' => 'id']) ?>
    <?= $form->field($model, 'name')->textInput(['placeholder' => '名称']) ?>
    <?= $form->field($model, 'type')->dropDownList(Coupon::$TYPE_MAP, ['prompt' => '类型']) ?>
    <?= $form->field($model, 'get_method')->dropDownList(Coupon::$GET_METHOD_MAP, ['prompt' => '获取方法']) ?>
    <?= $form->field($model, 'code')->textInput(['placeholder' => '优惠码']) ?>
    <?= $form->field($model, 'status')->dropDownList(Coupon::$STATUS_MAP, ['prompt' => '状态']) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
