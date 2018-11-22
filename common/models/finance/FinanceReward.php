<?php

namespace common\models\finance;

use common\models\Member\MemberLevel;
use Yii;

class FinanceReward extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_finance');
    }
    public static function tableName()
    {
        return 'finance_reward';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'record_id', 'level'], 'integer'],
            [['amount'], 'number'],
            [['date', 'created_at'], 'safe'],
            [['txt'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => 'ID',
            'user_id'    => '用户ID',
            'record_id'  => '记录ID',
            'level'      => '用户相差级别',
            'amount'     => '提成金额',
            'txt'        => '备注',
            'date'       => '开单时间',
            'created_at' => '创建时间',
        ];
    }

    /**
     * 给用户发放工资
     *
     * @param object  $record
     */
    public static function addOneSave($record){
        $userInfo = MemberLevel::findOne(['user_id' => $record->user_id]);
        $item['record_id'] = $record->id;
        $item['date'] = $record->date;
        $item['created_at'] = date('Y-m-d H:i:s');

        $indata = [];
        // 开单者
        if( $record->user > 0 && $userInfo->user_id){
            $indata[] = self::createItem($item, $userInfo->user_id, 0, $record->user, $record->title, '售出提成');
        }

        // 上级
        if( $record->parent > 0 && $userInfo->parent){
            $indata[] = self::createItem($item, $userInfo->parent, 1, $record->parent, $record->title, '下级售出提成');
        }

        // 上两级
        if( $record->grandpa > 0 && $userInfo->grandpa){
            $indata[] = self::createItem($item, $userInfo->grandpa, 2, $record->grandpa, $record->title, '下二级售出提成');
        }

        // 后台管理
        $indata[] = self::createItem($item, 1, 99, $record->admin, $record->title, '后台管理提成');

        // 团队管理
        if( $userInfo->teamids && $teamids = explode(',', $userInfo->teamids) ){
            $teamids = array_reverse($teamids);
            $level = 3;
            $teamamount = $record->team / count($teamids) ;
            foreach ($teamids as $uid){
                $indata[] = self::createItem($item, $uid, $level++, $teamamount, $record->title, '团队售出提成');
            }
        }

        static::find()->createCommand()->batchInsert(static::tableName(), array_keys($indata[0]), $indata)->execute();
    }

    private static function createItem($item, $uid, $level, $amount, $title, $txt){
        $item['user_id'] = $uid;
        $item['level'] = $level;
        $item['amount'] = $amount;
        $item['txt'] = "【{$title}】 $txt";
        return $item;
    }

}
