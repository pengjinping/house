<?php

use yii\db\Migration;

/**
 * Class m180717_104854_table_area
 */
class m180717_104854_area  extends Migration
{
    public function init()
    {
        $this->db = 'common';
        parent::init();
    }
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('area', [
            'code' => $this->string(6)->unique()->comment('区域ID'),
            'name' => $this->string('30')->comment('区域名称'),
            'level'=> $this->char(1)->comment('区域级别 0 全国 1 省 2 市 3区')
        ], "ENGINE=InnoDB CHARACTER SET utf8 COLLATE utf8_unicode_ci  COMMENT='区域管理'");
        $this->addPrimaryKey('code', 'area', 'code');

		
        $sql=<<<TAG
        INSERT INTO area SET code = 51, name='四川省', level=1;
        INSERT INTO area SET code = 5101, name='成都市', level=2;
        INSERT INTO area SET code = 510104, name='锦江区', level=3;
        INSERT INTO area SET code = 510105, name='青羊区', level=3;
        INSERT INTO area SET code = 510106, name='金牛区', level=3;
        INSERT INTO area SET code = 510107, name='武侯区', level=3;
        INSERT INTO area SET code = 510108, name='成华区', level=3;
        INSERT INTO area SET code = 510112, name='龙泉驿区', level=3;
        INSERT INTO area SET code = 510113, name='青白江区', level=3;
        INSERT INTO area SET code = 510114, name='新都区', level=3;
        INSERT INTO area SET code = 510115, name='温江区', level=3;
        INSERT INTO area SET code = 510121, name='金堂县', level=3;
        INSERT INTO area SET code = 510122, name='双流区', level=3;
        INSERT INTO area SET code = 510124, name='郫县', level=3;
        INSERT INTO area SET code = 510129, name='大邑县', level=3;
        INSERT INTO area SET code = 510131, name='浦江县', level=3;
        INSERT INTO area SET code = 510132, name='新津县', level=3;
        INSERT INTO area SET code = 510181, name='都江堰市', level=3;
        INSERT INTO area SET code = 510182, name='彭州市', level=3;
        INSERT INTO area SET code = 510183, name='邛崃市', level=3;
        INSERT INTO area SET code = 510184, name='崇州市', level=3;
TAG;
        $this->execute($sql);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('area');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180717_104854_table_area cannot be reverted.\n";

        return false;
    }
    */
}
