<?php

use yii\db\Migration;

/**
 * Class m181030_014432_grant_gift
 */
class m181030_014432_grant_gift extends Migration
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
        $this->createTable("grant", [
            "id" => $this->primaryKey(),
            "event" => $this->smallInteger(4)->comment('触发事件 1注册 2登录 3充值 4在线消费 5余额消费'),
            "type" => $this->smallInteger(4)->comment('赠送类型 1积分 2优惠券 3现金 4时间'),
            "method" => $this->smallInteger(4)->comment('赠送方式 1价值  2比例'),
            "value" => $this->integer(10)->comment('赠送价值（比例%）：如：注册赠送5个积分、充值赠送1%优惠'),
            "value_id" => $this->integer(10)->comment('类型为优惠券时有效 优惠券ID'),
            "condition" => $this->integer(10)->comment('赠送条件 如满100元送; 充值50元送'),
            "status" => $this->smallInteger(4)->comment('状态 1开启 0关闭'),
            "desc_txt" => $this->string(256)->comment('备注描述'),
            "start_date" => $this->dateTime()->comment('开始时间'),
            "end_date" => $this->dateTime()->comment('结束时间'),
            "created_at" => $this->dateTime()->comment('创建时间'),
            "updated_at" => $this->dateTime()->comment('更新时间'),
        ], "ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='赠送配置表'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
       return $this->dropTable("grant");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181030_014432_grant_gift cannot be reverted.\n";

        return false;
    }
    */
}
