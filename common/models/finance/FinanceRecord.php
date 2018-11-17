<?php

namespace common\models\finance;

use Yii;

class FinanceRecord extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_finance');
    }
    public static function tableName()
    {
        return 'finance_record';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'price', 'level_id'], 'integer'],
            [['channle', 'operate', 'user', 'parent', 'grandpa', 'team', 'league'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['title'], 'string', 'max' => 50],
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
            'user_id'    => '用户ID',
            'price'      => '开单金额',
            'level_id'   => '开单级别',
            'channle'    => '渠道',
            'operate'    => '运营',
            'user'       => '开单者',
            'parent'     => '上级',
            'grandpa'    => '二级',
            'team'       => '团队管理',
            'league'     => '团建',
            'date'       => '开单时间',
            'created_at' => '创建时间',
        ];
    }
}
