<?php

use yii\db\Migration;

/**
 * Class m181030_020447_grade
 */
class m181030_020447_grade extends Migration
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
        $this->createTable('grade', [
            'id' => $this->primaryKey(),
            'code' => $this->string(6)->unique()->comment('等级编号'),
            'name' => $this->string('30')->comment('等级名称'),
            'rate' => $this->integer(6)->comment('购物折扣')->defaultValue('100')
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='等级管理'");

        $sql=<<<TAG
        INSERT INTO grade SET code = 1, name='游客';
        INSERT INTO grade SET code = 2, name='普通会员';
        INSERT INTO grade SET code = 3, name='白银会员';
        INSERT INTO grade SET code = 4, name='黄金会员';
        INSERT INTO grade SET code = 5, name='铂金会员';
        INSERT INTO grade SET code = 99, name='超级会员';
TAG;

        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable("grade");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181030_020447_grade cannot be reverted.\n";

        return false;
    }
    */
}
