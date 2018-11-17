<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\product\BuildHouse;

?>

<div class="build-house-search search-wrap">

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
    <?= $form->field($model, 'title')->textInput(['placeholder' => '名称']) ?>
    <?= $form->field($model, 'area_id')->dropDownList(\common\models\Area::getCacheArea(3), ['prompt' => '区域'] ) ?>
    <?= $form->field($model, 'developer')->textInput(['placeholder' => '开发商']) ?>
    <?= $form->field($model, 'type')->dropDownList(BuildHouse::$TYPE_MAP, ['prompt' => '类型']) ?>
    <?= $form->field($model, 'status')->dropDownList(BuildHouse::$STATUS_MAP, ['prompt' => '状态']) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
