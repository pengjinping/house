<?php

namespace common\models\product;

use Yii;
use common\models\product\BuildContent;

class BuildHouse extends \yii\db\ActiveRecord
{
    //房屋类型
    const TYPE_HOUSE = 1;
    const TYPE_APARTMENT = 2;
    const TYPE_VILLA = 3;
    const TYPE_SHOPS = 4;
    const TYPE_OFFICE = 5;
    const TYPE_HOTEL = 6;
    const TYPE_WORKSHOP = 7;
    public static $TYPE_MAP = [
        self::TYPE_HOUSE     => '住宅',
        self::TYPE_APARTMENT => '公寓',
        self::TYPE_VILLA     => '别墅',
        self::TYPE_SHOPS     => '商铺',
        self::TYPE_OFFICE    => '写字楼',
        self::TYPE_HOTEL     => '酒店',
        self::TYPE_WORKSHOP  => '厂房',
    ];

    //房屋分类
    const RESOURCE_NEW = '1';
    const RESOURCE_OLD = '2';
    public static $RESOURCE_MAP = [
        self::RESOURCE_NEW => '新房',
        self::RESOURCE_OLD => '二手房',
    ];

    //状态
    const STATUS_EXPIRE = 0;
    const STATUS_DISPLAY = 1;
    const STATUS_HIDE = 2;
    public static $STATUS_MAP = [
        self::STATUS_DISPLAY => '可见',
        self::STATUS_HIDE    => '隐藏',
        self::STATUS_EXPIRE  => '过期',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_product');
    }
    public static function tableName()
    {
        return 'build';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['price', 'total', 'size', 'top'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['title', 'address', 'village', 'developer'], 'string', 'max' => 50],
            [['image', 'grade_ids'], 'string', 'max' => 100],
            [['images'], 'string', 'max' => 500],
            [['area_id'], 'string', 'max' => 6],
            [['type', 'resource', 'status'], 'string', 'max' => 1],
            [['flag', 'leader', 'lead_phone', 'stater', 'state_phone', 'rate'], 'string', 'max' => 20],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'title'      => '名称',
            'image'      => '缩略图',
            'images'     => '简介图',
            'address'    => '地址',
            'area_id'    => '区域',
            'village'    => '小区名称',
            'developer'  => '开发商',
            'type'       => '类型',
            'resource'   => '分类',
            'price'      => '单价(/平方)',
            'total'      => '总价（万）',
            'size'       => '面积',
            'flag'       => '标记',
            'top'         => '排序',
            'status'      => '状态',
            'rate'        => '佣金',
            'leader'      => '负责人',
            'lead_phone'  => '负责电话',
            'stater'      => '驻场人',
            'state_phone' => '驻场电话',
            'grade_ids'   => '可访问等级',
            'created_at'  => '发布时间',
            'updated_at'  => '更新时间',
        ];
    }

    /**
     * 查询房源详情
     */
    public function getImageses(){
        return explode("\r\n", $this->images);
    }

    /**
     * 查询房源详情
     */
    public function getContent(){
        return $this->hasOne(BuildContent::className(), ['build_id' => 'id' ]);
    }
}
