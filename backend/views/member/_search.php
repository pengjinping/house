<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

use \common\models\Member\Member;
?>

<div class="member-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'id')->textInput(['placeholder'=>'用户ID'])?>

    <?= $form->field($model, 'username')->textInput(['placeholder'=>'用户名'])?>

    <?= $form->field($model, 'nick')->textInput(['placeholder'=>'昵称'])?>

    <?= $form->field($model, 'idcard')->textInput(['placeholder'=>'身份证'])?>

    <?= $form->field($model, 'mobile')->textInput(['placeholder'=>'手机号'])?>

    <?= $form->field($model, 'status')->dropDownList(Member::$STATUS_MAP, ['prompt' => '状态'])?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
