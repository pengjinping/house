<?php

use yii\db\Migration;

/**
 * Class m181030_015302_coupon
 */
class m181030_015302_coupon extends Migration
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
        $this->createTable("coupon", [
            "id" => $this->primaryKey(),
            "name" => $this->string('50')->comment('优惠券名称'),
            "desc_txt" => $this->string(1000)->comment('备注描述'),
            "expire_days" => $this->smallInteger(4)->comment('领取后多少天内失效')->defaultValue(7),
            "total_count" => $this->integer(10)->comment('最高发行多少张券')->defaultValue(9999),
            "pick_count" => $this->integer(10)->comment('已经领取了多少张')->defaultValue(0),
            "limit" => $this->integer(10)->comment('每个人最多领多少张券')->defaultValue(1),
            "type" => $this->smallInteger(4)->comment('优惠券类型 1:抵扣券 2:折扣券')->defaultValue(1),
            "full" => $this->integer(10)->comment('满多少可用'),
            "value" => $this->integer(10)->comment('券的面值，如果是折扣则填写折扣价值95'),
            "product_type" => $this->string(255)->comment('适用的类型 考试、视频、文档、商品'),
            "product_id" => $this->string(255)->comment('适用的产品id,英文逗号分隔'),
            "get_method" => $this->smallInteger(255)->comment('获取方法 1:优惠码获取 2:系统赠送 3:消费获取 4:注册赠送 5:充值赠送'),
            "code" => $this->string(10)->comment('优惠码 系统生成'),
            "del_flag" => $this->smallInteger(4)->comment('删除标记 0未删除 1已删除')->defaultValue(0),
            "status" => $this->smallInteger(4)->comment('状态 1开启 0关闭'),
            "begin_time" => $this->date()->comment('开始时间'),
            "expire_time" => $this->date()->comment('过期时间'),
            "created_at" => $this->dateTime()->comment('创建时间'),
            "updated_at" => $this->dateTime()->comment('更新时间'),
        ], "ENGINE=MyISAM CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='优惠券配置'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        return $this->dropTable("coupon");
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181030_015302_coupon cannot be reverted.\n";

        return false;
    }
    */
}
