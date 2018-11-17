<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClass = ltrim($generator->modelClass, '\\');
$modelClassName = basename($modelClass);

$model = new $generator->modelClass();
$safeAttributes = $model->safeAttributes();
if (empty($safeAttributes)) {
    $safeAttributes = $model->attributes();
}

$tableSchema = $generator->getTableSchema();
echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use <?= $modelClass ?>;

?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-form">

    <?= "<?php " ?>$form = ActiveForm::begin([
    	'options' => ['class' => 'form-horizontal'],
    	'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{label}\n<div class=\"col-sm-8\">{input}\n<span class=\"help-block m-b-none\">{error}</span></div>",
            'labelOptions' => ['class' => 'col-sm-2 control-label'],
    	]
    ]); ?>

<?php foreach ($generator->getColumnNames() as $attribute) {
    if (!in_array($attribute, $safeAttributes) || in_array($attribute, $generator->getIgnoreAttribute())) {
        continue;
    }
    $comment = $tableSchema->columns[$attribute]->comment;
    $comments = explode(' ', $comment);
    if( count($comments) > 2 ){
        $type = "->dropDownList({$modelClassName}::$".strtoupper($attribute) ."_MAP)";
    }else{
        $type = "->textInput(['maxlength' => true])";
    }
    echo "    <?= " . $generator->generateActiveSearchField($attribute) . "$type ?>\n\n";
} ?>
   <!--
    $form->field($model, 'content')->widget(\zh\qiniu\QiniuFileInput::className(),[ 'qlConfig' => \Yii::$app->params['ql'],
        'uploadUrl' => 'https://upload.qiniup.com',
        'clientOptions' => [ 'max'=>1 ] ])->label('图片')
    $form->field($model, 'content')->widget('kucha\ueditor\UEditor')->label('内容')
    use kartik\datetime\DateTimePicker;
    use kartik\date\DatePicker;
    $form->field($model, 'created_at')->widget(DateTimePicker::classname(), [  DateTimePicker||DatePicker
        ['options' => ['placeholder' => ''], 'pluginOptions' => ['format' => 'yyyy-mm-dd hh:ii:ss', 'todayHighlight' => true, 'autoclose' => true] ]);
    -->
    <div class="form-group">
    	<div class="col-sm-4 col-sm-offset-2">
        	<?= "<?= " ?>Html::submitButton($model->isNewRecord ? <?= $generator->generateString('创建') ?> : <?= $generator->generateString('更新') ?>, ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    	</div>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
