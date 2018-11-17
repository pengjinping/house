<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClass= ltrim($generator->modelClass, '\\');
$modelClassName = basename($modelClass);

echo "<?php\n";
?>

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use <?= ltrim($generator->modelClass, '\\') ?>;

?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-search search-wrap">

    <?= "<?php " ?>$form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => ['class' => 'form-inline'],
        'fieldConfig' => [
            'options' => ['class' => 'form-group'],
            'template' => "{input}"
        ],
    ]); ?>

<?php
$count = 0;
foreach ($generator->getColumnNames() as $attribute) {
    $str =  $generator->tableSchema->columns[$attribute]->comment == ''  ? $generator->tableSchema->columns[$attribute]->name :  $generator->tableSchema->columns[$attribute]->comment;
    $comments = explode(' ', $str);
    if( count($comments) > 2 ){
        $type = "->dropDownList({$modelClassName}::$".strtoupper($attribute) ."_MAP, ['prompt' => '{$comments[0]}'])";
    }else{
        $type = "->textInput(['placeholder' => '{$comments[0]}'])";
    }
    echo "    <?= " . $generator->generateActiveSearchField($attribute) . "$type ?>\n";
}
?>
    <!-- dropDownList(STATUS_MAP, ['prompt' => '游戏状态']) -->
    <div class="form-group">
        <?= "<?= " ?>Html::submitButton(<?= $generator->generateString('搜索') ?>, ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::resetButton(<?= $generator->generateString('重置') ?>, ['class' => 'btn btn-default']) ?>
    </div>

    <?= "<?php " ?>ActiveForm::end(); ?>

</div>
<hr />
