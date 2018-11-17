<?php

namespace common\models\product;

use Yii;

class BuildNews extends \yii\db\ActiveRecord
{
    //状态
    const STATUS_OFF = 0;
    const STATUS_ON = 1;
    public static $STATUS_MAP = [
        self::STATUS_OFF => '禁用',
        self::STATUS_ON  => '启用',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_product');
    }
    public static function tableName()
    {
        return 'news';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['status'], 'integer'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'title'      => '标题',
            'url'        => '原始URL',
            'content'    => '内容',
            'status'     => '状态',
            'created_at' => '入库时间',
        ];
    }
}
