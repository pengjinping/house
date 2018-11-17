<?php

use yii\db\Migration;

/**
 * Class m181104_042909_finance
 */
class m181104_042909_finance extends Migration
{
    public function init()
    {
        $this->db = 'db_finance';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        /*$this->createTable('finance_level', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->comment('名称'),
            'min' => $this->integer(11)->comment('最小值'),
            'max' => $this->integer(11)->comment('最大值'),
            'channle' => $this->decimal(6,2)->comment('渠道'),
            'operate' => $this->decimal(6,2)->comment('运营'),
            'user' => $this->decimal(6,2)->comment('开单者'),
            'parent' => $this->decimal(6,2)->comment('上级'),
            'grandpa' => $this->decimal(6,2)->comment('二级'),
            'league' => $this->decimal(6,2)->comment('团建'),
            'team' => $this->decimal(6,2)->comment('团队管理'),
            'status' => $this->char(1)->comment('状态 0禁用 1启用')
        ],"ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='等级分成'");*/

        $this->createTable('finance_record', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->comment('名称'),
            'user_id' => $this->integer(50)->comment('用户ID'),
            'price' => $this->integer(11)->comment('开单金额'),
            'level_id' => $this->integer(11)->comment('开单级别'),
            'channle' => $this->decimal(8,2)->comment('渠道'),
            'operate' => $this->decimal(8,2)->comment('运营'),
            'user' => $this->decimal(8,2)->comment('开单者'),
            'parent' => $this->decimal(8,2)->comment('上级'),
            'grandpa' => $this->decimal(8,2)->comment('二级'),
            'team' => $this->decimal(8,2)->comment('团队管理'),
            'league' => $this->decimal(8,2)->comment('团建'),
            'date' => $this->date()->comment('开单时间'),
            'created_at' => $this->dateTime()->comment('创建时间'),
        ],"ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='开单记录表'");

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181104_042909_finance cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181104_042909_finance cannot be reverted.\n";

        return false;
    }
    */
}
