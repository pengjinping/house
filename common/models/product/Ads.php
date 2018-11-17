<?php

namespace common\models\product;

use Yii;

class Ads extends \yii\db\ActiveRecord
{
    //类型
    const TYPE_OUT = 1;
    const TYPE_HOUSE_NEW = 2;
    const TYPE_HOUSE_OLD = 3;
    const TYPE_HOUSE_RENT = 4;
    const TYPE_NEWS = 5;
    public static $TYPE_MAP = [
        self::TYPE_OUT        => '外部',
        self::TYPE_HOUSE_NEW  => '新房',
        self::TYPE_HOUSE_OLD  => '二手房',
        self::TYPE_HOUSE_RENT => '租房',
        self::TYPE_NEWS       => '资讯',
    ];

    //状态
    const STATUS_ON = 1;
    const STATUS_OFF = 0;
    public static $STATUS_MAP = [
        self::STATUS_ON  => '开启',
        self::STATUS_OFF => '禁用',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_product');
    }
    public static function tableName()
    {
        return 'ads';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'num', 'status'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['url', 'image'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'type'       => '类型',
            'title'      => '标题',
            'url'        => 'URL地址',
            'image'      => '图片地址',
            'num'        => '排序',
            'status'     => '状态',
            'created_at' => '创建时间',
        ];
    }
}
