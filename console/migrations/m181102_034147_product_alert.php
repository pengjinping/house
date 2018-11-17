<?php

use yii\db\Migration;
use common\models\product\BuildHouse;

/**
 * Class m181102_034147_product_alert
 */
class m181102_034147_product_alert extends Migration
{
    public function init()
    {
        $this->db = 'db_product';
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn(BuildHouse::tableName(), 'rate', "varchar(20) DEFAULT '' COMMENT '佣金'");
        $this->addColumn(BuildHouse::tableName(), 'leader', "varchar(10) DEFAULT '' COMMENT '负责人'");
        $this->addColumn(BuildHouse::tableName(), 'lead_phone', "varchar(11) DEFAULT '' COMMENT '负责电话'");
        $this->addColumn(BuildHouse::tableName(), 'stater', "varchar(10) DEFAULT '' COMMENT '驻场人'");
        $this->addColumn(BuildHouse::tableName(), 'state_phone', "varchar(11) DEFAULT '' COMMENT '驻场电话'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn(BuildHouse::tableName(), 'rate');
        $this->dropColumn(BuildHouse::tableName(), 'leader');
        $this->dropColumn(BuildHouse::tableName(), 'lead_phone');
        $this->dropColumn(BuildHouse::tableName(), 'stater');
        $this->dropColumn(BuildHouse::tableName(), 'state_phone');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181102_034147_product_alert cannot be reverted.\n";

        return false;
    }
    */
}
