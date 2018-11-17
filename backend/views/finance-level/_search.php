<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\finance\FinanceLevel;

?>

<div class="finance-level-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => '名称']) ?>
    <?= $form->field($model, 'min')->textInput(['placeholder' => '最小值']) ?>
    <?= $form->field($model, 'max')->textInput(['placeholder' => '最小值']) ?>
    <?= $form->field($model, 'status')->dropDownList(FinanceLevel::$STATUS_MAP, ['prompt' => '状态']) ?>
    <!-- dropDownList(STATUS_MAP, ['prompt' => '游戏状态']) -->
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
