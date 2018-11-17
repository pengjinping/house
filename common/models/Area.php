<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "area".
 *
 * @property string $code 区域ID
 * @property string $name 区域名称
 * @property string $level 区域级别 0 全国 1 省 2 市 3区
 */
class Area extends \yii\db\ActiveRecord
{

    public static $LEVEL_MAP = array('全国', '省', '市', '县');

    /**
     * {@inheritdoc}
     */
    public static function getDb()
    {
        return Yii::$app->get("common");
    }
    public static function tableName()
    {
        return 'area';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 30],
            [['level'], 'string', 'max' => 1],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => '区域ID',
            'name' => '区域名称',
            'level' => '区域级别',
        ];
    }

    // 查询地域信息内容 array | 名称
    public static function getCacheArea($level, $code=''){
        $arealist = static::find()->where(['level' => $level])->asArray()->indexBy('code')->cache(300)->all();
        if($code > 0){
            return isset($arealist[$code]) ? $arealist[$code]['name'] : '';
        }
        $newlist = [];
        foreach ($arealist as $k => $v){
            $newlist[$k] = $v['name'];
        }
        return $newlist;
    }
}
