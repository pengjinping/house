<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Member\Grade;

?>

<div class="grade-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'code')->textInput(['placeholder' => '等级编号']) ?>
    <?= $form->field($model, 'name')->textInput(['placeholder' => '等级名称']) ?>
    <?= $form->field($model, 'rate')->textInput(['placeholder' => '购物折扣']) ?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
