<?php

use yii\db\Migration;

/**
 * Class m180717_093922_table_member
 */
class m180717_093922_member extends Migration
{
    public function init()
    {
        $this->db = 'db_member';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('member', [
            "id" => $this->primaryKey()->comment('用户ID'),
            'username' => $this->string(20)->comment('用户名'),
            'password' => $this->string(32)->comment('登陆密码'),
            'pay_password' => $this->string(32)->comment('支付密码'),
            'nick' => $this->string(20)->comment('用户昵称'),
            'idcard' => $this->string(18)->comment('用户身份证'),
            'realname' => $this->string(10)->comment('真实姓名'),
            'mobile' => $this->string(11)->comment('手机号'),
            'sex' => $this->char(1)->comment('用户性别 0 女 1 男 2 保密')->defaultValue(2),
            'wx_openid' => $this->string(32)->comment('微信OPEN_ID'),
            'wx_unoin' => $this->string(32)->comment('微信服务号ID'),
            'qq_openid' => $this->string(32)->comment('QQOPEN_ID'),
            'headimg' => $this->string(100)->comment('用户头像'),
            'grade_id' => $this->integer(11)->comment('用户等级')->defaultValue(0),
            'token' => $this->string(64)->comment('验证字符串'),
            'status' => $this->integer(2)->comment('状态 0 禁用 1 正常')->defaultValue(1),
            'created_at' => $this->datetime()->comment('注册时间'),
            'updated_at' => $this->datetime()->comment('更新时间'),
        ], "ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='用户表'");
        $this->createIndex('wx_openid', 'member', 'wx_openid');
        $this->createIndex('qq_openid', 'member', 'qq_openid');
        $this->createIndex('username', 'member', 'username');
        $this->createIndex('mobile', 'member', 'mobile');

		$user = new \common\models\Member\Member();
        $user->username = 'king';
		$user->password = '123';
		$user->pay_password = '123';
		$user->mobile = '13540861510';
		$user->idcard = 'idcard';
        $user->nick = 'king';
		$user->realname = 'realname';
		$user->sex = '2';
		$user->wx_openid = 'wx_openid';
		$user->wx_unoin = 'wx_unoin';
		$user->qq_openid = 'qq_openid';
		$user->headimg = 'headimg';
		$user->grade_id = 0;
		$user->token = 'headimg';
		$user->status = 1;
        $user->created_at = date('Y-m-d H:');
        $user->updated_at = date('Y-m-d H:');
        $user->save();
		
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable('member');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180717_093922_table_member cannot be reverted.\n";

        return false;
    }
    */
}
