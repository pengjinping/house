<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\search\AccountService */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="area-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'code')->textInput(['placeholder'=>'区域ID'])?>

    <?= $form->field($model, 'name')->textInput(['placeholder'=>'区域名称'])?>

    <?= $form->field($model, 'level')->dropDownList(\common\models\Area::$LEVEL_MAP, ['prompt' => '区域级别'])?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
