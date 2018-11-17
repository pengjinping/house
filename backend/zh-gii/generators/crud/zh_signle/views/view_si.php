<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

/* @var $this yii\web\View */
/* @var $generator yii\gii\generators\crud\Generator */

$urlParams = $generator->generateUrlParams();
$tableComments = $generator->getTableComments();
echo "<?php\n";
?>

use yii\helpers\Html;
use <?= ltrim($generator->modelClass, '\\') ?>;

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $tableComments ?>列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<style>

</style>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view  wrapper wrapper-content animated fadeInRigh">
	<div class="ibox-content col-sm-8 col-sm-offset-2 page">
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>
        
    <table style="text-align:left" class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align:center" colspan="3"><h1>详情查看</h1></th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($generator->getTableSchema()->columns as $key=>$v):?>
        	<tr><td><?php 
	            $str = $v->comment == '' ? $key : $v->comment;
	            echo explode(';', $str)[0];
	         ?></td><td><?= '<?=Html::encode($model->'.$key.');?>'; ?></td></tr>
<?php endforeach;?>
        </tbody>
    </table>
	</div>
</div>
