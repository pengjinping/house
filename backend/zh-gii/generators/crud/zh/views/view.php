<?php

use yii\helpers\Inflector;
use yii\helpers\StringHelper;

$modelClass= ltrim($generator->modelClass, '\\');
$modelClassName = basename($modelClass);
$urlParams = $generator->generateUrlParams();
$tableComments = $generator->getTableComments();
echo "<?php\n";
?>

use yii\helpers\Html;
use <?= $modelClass ?>;

$this->title = $model-><?= $generator->getNameAttribute() ?>;
$this->params['breadcrumbs'][] = ['label' => '<?= $tableComments ?>列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="<?= Inflector::camel2id(StringHelper::basename($generator->modelClass)) ?>-view  wrapper wrapper-content animated fadeInRigh">
	<div class="ibox-content col-sm-8 col-sm-offset-2 page">
    <h1><?= "<?= " ?>Html::encode($this->title) ?></h1>

    <p>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('修改') ?>, ['update', <?= $urlParams ?>], ['class' => 'btn btn-primary']) ?>
        <?= "<?= " ?>Html::a(<?= $generator->generateString('删除') ?>, ['delete', <?= $urlParams ?>], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => <?= $generator->generateString('你确定要删除?') ?>,
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <table style="text-align:left" class="table table-bordered">
        <thead>
            <tr>
                <th style="text-align:center" colspan="3"><h1>详情查看</h1></th>
            </tr>
        </thead>
        <tbody>
<?php foreach ($generator->getTableSchema()->columns as $key=>$v) {
    $str     = $v->comment == '' ? $key : $v->comment;
    $comment = explode(' ', $str);
    echo "\n\t\t<tr><td>{$comment[0]}</td><td>";
    if (count($comment) > 2) {
        echo '<?= Html::encode(' . $modelClassName . '::$' . strtoupper($key) . '_MAP[$model->' . $key . ']);?></td></tr>';
    } else {
        echo '<?= Html::encode($model->' . $key . '); ?></td></tr>';
    }
} ?>
        </tbody>
    </table>
	</div>
</div>
