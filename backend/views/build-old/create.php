<?php

use common\models\product\BuildHouse;

$this->title = '创建';
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="build-house-create col-sm-12">
	<div class=" ibox ibox-content" style="margin-top: 15px;">
    <?= $this->render('_form', [
        'model' => $model,
        'modelContent' => $modelContent
    ]) ?>
	</div>
</div>
