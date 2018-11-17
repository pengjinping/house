<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\product\BuildNews;

?>

<div class="build-news-search search-wrap">

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
    <?= $form->field($model, 'title')->textInput(['placeholder' => '标题']) ?>
    <?= $form->field($model, 'url')->textInput(['placeholder' => '原始URL']) ?>
    <?= $form->field($model, 'status')->dropDownList(BuildNews::$STATUS_MAP, ['prompt' => '状态']) ?>
    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
