<?php

namespace common\models\Member;

use Yii;

class Grade extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'grade';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['rate'], 'integer'],
            [['code'], 'string', 'max' => 6],
            [['name'], 'string', 'max' => 30],
            [['code'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => '等级编号',
            'name' => '等级名称',
            'rate' => '购物折扣(%)',
        ];
    }
}
