<?php

use yii\db\Migration;
use backend\models\User;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string()->notNull()->unique()->comment('用户姓名'),
            'phone' => $this->string()->notNull()->unique()->comment('联系方式'),
            'auth_key' => $this->string(32)->notNull()->comment('Auth_key验证字符串'),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique()->comment('用户邮箱'),
            'status' => $this->smallInteger()->notNull()->defaultValue(1)->comment('状态 0停用 1正常'),
            'created_at' => $this->integer()->notNull()->comment('开始时间'),
            'updated_at' => $this->integer()->notNull()->comment('修改时间'),
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='管理员表'");

        $user = new User();
        $user->username = 'admin';
        $user->setPassword('admin123');
        $user->phone = '13540861510';
        $user->auth_key = 1;
        $user->status = '1';
		$user->email = '1';
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();

        $user = new User();
        $user->username = 'user';
        $user->setPassword('user4456123');
        $user->auth_key = 1;
        $user->phone = '13540861511';
        $user->status = '1';
		$user->email = '2';
        $user->created_at = date('Y-m-d H:i:s');
        $user->updated_at = date('Y-m-d H:i:s');
        $user->save();
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
    }
}
