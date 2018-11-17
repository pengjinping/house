<?php

use yii\db\Migration;

/**
 * Class m181031_074119_product
 */
class m181031_074119_product extends Migration
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
        $this->createTable('build', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->comment('名称'),
            'image' => $this->string(100)->comment('缩略图'),
            'images' => $this->string(500)->comment('简介图 最多5张'),
            'address' => $this->string(50)->comment('地址'),
            'area_id' => $this->string(6)->comment('区域'),
            'village' => $this->string(50)->comment('小区名称'),
            'developer' => $this->string(50)->comment('开发商'),
            'type' => $this->char(1)->comment('类型 1住宅 2公寓 3别墅 4商铺 5写字楼 6酒店 7厂房'),
            'resource' => $this->char(1)->comment('分类 1新房 2 二手房'),
            'price' => $this->smallInteger(6)->comment('单价'),
            'total' => $this->smallInteger(6)->comment('总价（万）'),
            'size' => $this->smallInteger(6)->comment('面积大小'),
            'flag' => $this->string(20)->comment('标记'),
            'top' => $this->smallInteger(6)->comment('热门排序')->defaultValue(0),
            'status' => $this->char(1)->comment('状态 0过期 1可见 2隐藏'),
            'grade_ids' => $this->string(100)->comment('可访问用户等级'),
            'created_at' => $this->dateTime()->comment('发布时间'),
            'updated_at' => $this->dateTime()->comment('更新时间')
        ],"ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='房源资讯'");

        $this->createTable('build_content', [
            'id' => $this->primaryKey(),
            'build_id' => $this->integer(11)->comment('信息ID'),
            'content' => $this->text()->comment('详细内容'),
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='房源内容'");

        $this->createTable('news', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->comment('标题'),
            'url' => $this->string(100)->comment('原始URL'),
            'content' => $this->text()->comment('内容'),
            'status' => $this->smallInteger(6)->comment('状态 0禁用 1启用'),
            'created_at' => $this->dateTime()->comment('入库时间')
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci COMMENT='最新资讯'");
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('build');
        $this->dropTable('build_content');
        $this->dropTable('news');
        return true;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181031_074119_product cannot be reverted.\n";

        return false;
    }
    */
}
