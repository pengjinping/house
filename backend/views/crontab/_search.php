<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use common\models\Crontab\Crontab;
?>

<div class="crontab-search search-wrap">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

    <?= $form->field($model, 'name') ->textInput(['placeholder'=>'定时任务名称'])?>

    <?= $form->field($model, 'route') ->textInput(['placeholder'=>'任务路由'])?>

    <?= $form->field($model, 'switch') ->dropDownList(Crontab::$switchTextMap, ['prompt' => '状态'])?>

    <div class="form-group">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
<hr />
