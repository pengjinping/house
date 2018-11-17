<?php

use yii\db\Migration;

/**
 * Class m181030_063447_member_coupon
 */
class m181030_063447_member_coupon extends Migration
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
        $this->createTable('member_coupon', [
            "id" => $this->primaryKey(),
            'user_id' => $this->integer(10)->comment('用户id'),
            'coupon_id' => $this->integer(10)->comment('优惠券ID'),
            'product_type' => $this->string(100)->comment('产品类型 考试 视频 文档 商品'),
            'product_id' => $this->string(256)->comment('适用的产品id,英文逗号分隔'),
            'coupon_type' => $this->smallInteger(4)->comment('优惠券类型 1:抵扣券 2:折扣券'),
            'coupon_full' => $this->integer(10)->comment('使用条件满多少使用'),
            'coupon_value' => $this->integer(10)->comment('优惠券值'),
            'expire_time' => $this->datetime()->comment('过期时间'),
            'status' => $this->smallInteger(4)->comment('状态 0:失效 1:未使用 2:冻结中 3:已使用 4:已退回')->defaultValue(1),
            'pickup_scene' => $this->string(10)->comment('领取的场景编码，用来统计不同渠道的 注册|登录...'),
            'created_at' => $this->datetime()->comment('新增时间'),
            'updated_at' => $this->datetime()->comment('更新时间'),
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='用户的优惠券表'");

        $this->createTable('member_account', [
            'user_id' => $this->integer(10)->comment('用户id'),
            'cash' => $this->integer(10)->comment('现金余额'),
            'dist' => $this->integer(10)->comment('分销余额'),
            'give' => $this->integer(10)->comment('赠送余额'),
            'point' => $this->integer(10)->comment('积分'),
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='用户账户'");
        $this->addPrimaryKey('user_id', 'member_account', 'user_id');

        $this->createTable('member_record', [
            "id" => $this->primaryKey(),
            'user_id' => $this->integer(10)->comment('用户id'),
            'type' => $this->smallInteger(4)->comment('交易类型 1现金 2线上 3分销 4优惠 5积分'),
            'num' => $this->integer(10)->comment('交易数量'),
            'before' => $this->integer(10)->comment('交易之前'),
            'after' => $this->integer(10)->comment('交易之后'),
            'sn' => $this->string(32)->comment('订单ID'),
            'business' => $this->string(10)->comment('业务类型 注册 签到 充值 提现 下单'),
            'desctxt' => $this->string(255)->comment('描述信息'),
            'created_at' => $this->datetime()->comment('新增时间'),
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='账户变化记录'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('member_coupon');
        $this->dropTable('member_account');
        $this->dropTable('member_record');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181030_063447_member_coupon cannot be reverted.\n";

        return false;
    }
    */
}
