<?php

namespace common\models\Member;

use Yii;

class MemberAccount extends \yii\db\ActiveRecord
{
    const ACCOUNT_CASH = 'cash';
    const ACCOUNT_DIST = 'dist';
    const ACCOUNT_GIVE = 'give';
    const ACCOUNT_POINT = 'point';
    public static $ACCOUNT_MAP = [
        self::ACCOUNT_CASH => '现金',
        self::ACCOUNT_DIST => '分销余额',
        self::ACCOUNT_GIVE => '赠送余额',
        self::ACCOUNT_POINT => '积分',
    ];

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'member_account';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id'], 'required'],
            [['user_id', 'cash', 'dist', 'give', 'point'], 'integer'],
            [['user_id'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '用户id',
            'cash' => '现金余额',
            'dist' => '分销余额',
            'give' => '赠送余额',
            'point' => '积分',
        ];
    }

    /**
     * 增加用户账户流水
     * @param        $uid  用户ID
     * @param        $value 变化价值
     * @param        $type  变化类型 【cash point give 】
     * @param        $logtype 业务类型【现金 积分等等】
     * @param        $scene  场景 【注册|登录】
     * @param string $desc   详细描述
     * @param string $sn    订单ID
     *
     * @return bool|string
     */
    public static function addRowList($uid, $value, $type, $logtype, $scene, $desc='', $sn=''){
        $account = static::findOne(['user_id' => $uid]);
        if( empty($account) ){
            $account = self::createNew($uid);
        }

        $before = $account->$type;
        $after = $account->$type + $value;
        if( $after < 0 ){
            return '余额不足';
        }

        $account->$type = $after;

        $accountRecord = new MemberRecord();
        $accountRecord->user_id = $uid;
        $accountRecord->type = $logtype;
        $accountRecord->num = $value;
        $accountRecord->before = $before;
        $accountRecord->after = $after;
        $accountRecord->sn = $sn;
        $accountRecord->business = $scene;
        $accountRecord->desctxt = $desc ?? $scene;
        $accountRecord->created_at = date('Y-m-d H:i:s');

        if($accountRecord->save() && $account->save()){
            return true;
        }else{
            return $accountRecord->errors[0];
        }
    }
    /**
     * 增加用户账户流水
     */
    public static function createNew($uid){
        $account = new static();
        $account->user_id = $uid;
        $account->cash = 0;
        $account->dist = 0;
        $account->give = 0;
        $account->point = 0;
        return $account;
    }


}
