<?php
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

\backend\assets\LoginAssset::register($this);
$this->title = 'login'
?>

<?php $this->beginPage() ?>
    <!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
<body class="gray-bg">
<?php $this->beginBody() ?>
<?php if (YII_ENV == 'dev'):?>
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">Game</h1>

            </div>
            <h3>欢迎使用 </h3>
            <?php $form = ActiveForm::begin(['id' => 'login-form', 'options' => ['class' => 'm-t']]); ?>
            <div class="form-group">
                <?= $form->field($model, 'username')
                    ->textInput(['autofocus' => true, 'placeholder' => "用户名"])
                    ->label(false) ?>
            </div>
            <div class="form-group">
                <?= $form->field($model, 'password')
                    ->passwordInput(['autofocus' => false, 'placeholder' => "密码"])
                    ->label(false) ?>
            </div>
            <?= Html::submitButton('登录', [
                'class' => 'btn btn-primary block full-width m-b',
                'id'    => 'loginBtn',
                'name'  => 'login-button'
            ]) ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
<?php else:?>
    <div class="middle-box text-center loginscreen  animated fadeInDown">
        <div>
            <div>

                <h1 class="logo-name">Kn</h1>

            </div>
            <h3>欢迎使用</h3>
            <a href="/user/auth?authclient=wechat" class="btn bg-primary btn-social btn-dropbox">
                <i class="fa fa-weixin"></i> 使用企业通行证登录
            </a>
        </div>
    </div>
<?php endif;?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>