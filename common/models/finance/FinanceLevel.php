<?php

namespace common\models\finance;

use Yii;
use yii\web\BadRequestHttpException;

class FinanceLevel extends \yii\db\ActiveRecord
{
    //状态
    const STATUS_OFF = '0';
    const STATUS_ON = '1';
    public static $STATUS_MAP = [
        self::STATUS_ON  => '启用',
        self::STATUS_OFF => '禁用',
    ];


    public static function getDb()
    {
        return Yii::$app->get('db_finance');
    }
    public static function tableName()
    {
        return 'finance_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min', 'max'], 'integer'],
            [['channle', 'operate', 'user', 'parent', 'grandpa', 'league', 'team', 'admin'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['status'], 'string', 'max' => 1],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'      => 'ID',
            'title'   => '名称',
            'min'     => '最小值',
            'max'     => '最大值',
            'channle' => '渠道(%)',
            'operate' => '运营(%)',
            'user'    => '开单者(%)',
            'parent'  => '上级(%)',
            'grandpa' => '二级(%)',
            'admin'  => '后台管理(%)',
            'league'  => '团建(%)',
            'team'    => '团队管理(%)',
            'status'  => '状态',
        ];
    }


    /**
     *  校验比例分成是否满足100%
     * @param $model
     */
    public function checkRate(){

        $rate1 = $this->channle + $this->operate;
        $rate2 = $this->user + $this->parent + $this->grandpa + $this->admin + $this->league;
        if( $rate1 != 30  || $rate2 != 70 ){
            throw new BadRequestHttpException('抽成比例设置是吧，比例分配不合法');
        }
    }

    /**
     *  根据价格查找等级对象
     * @param $model
     */
    public static function findByPrice($price){
        if($price <= 0 ) return null;

        return static::find()->where(['status' => self::STATUS_ON])
            ->andFilterWhere(['<=', 'min', $price])
            ->andFilterWhere(['>', 'max', $price])
            ->asArray()
            ->one();
    }
}
