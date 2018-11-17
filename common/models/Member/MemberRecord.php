<?php

namespace common\models\Member;

use Yii;

class MemberRecord extends \yii\db\ActiveRecord
{
    //交易类型
    const TYPE_CASH = 1;
    const TYPE_ONLINE = 2;
    const TYPE_DIST  = 3;
    const TYPE_GIVE  = 4;
    const TYPE_POINT = 5;
    public static $TYPE_MAP = [
         self::TYPE_CASH => '现金',
         self::TYPE_ONLINE => '线上交易',
         self::TYPE_DIST => '分销',
         self::TYPE_GIVE => '优惠赠送',
         self::TYPE_POINT => '积分',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'member_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'type', 'num', 'before', 'after'], 'integer'],
            [['created_at'], 'safe'],
            [['sn'], 'string', 'max' => 32],
            [['business'], 'string', 'max' => 10],
            [['desctxt'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户id',
            'type' => '交易类型',
            'num' => '交易数量',
            'before' => '交易之前',
            'after' => '交易之后',
            'sn' => '订单ID',
            'business' => '业务类型',
            'desctxt' => '描述信息',
            'created_at' => '新增时间',
        ];
    }
}
