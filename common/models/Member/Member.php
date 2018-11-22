<?php

namespace common\models\Member;

use common\helpers\RedisHelper;
use common\helpers\StringHelper;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class Member extends ActiveRecord implements IdentityInterface
{
    const SESSION_KEY = '_memberid';  //用户登录后保存session的数组下标
    const LOGIN_TIME_DURATION = 86400;  //用户登录有效时间

    const STATUS_ONLINE = 1;
    const STATUS_DELETE = 0;
    const STATUS_WRITE = 2;
    public static $STATUS_MAP = array(
        self::STATUS_DELETE => '禁用',
        self::STATUS_ONLINE => '可用',
        self::STATUS_WRITE  => '审核',
    );

    const SEX_FEMALE  = 2;
    const SEX_MALE    = 1;
    const SEX_SECRECY = 0;
    public static $SEX_MAP = array(
        self::SEX_MALE    => '男',
        self::SEX_FEMALE  => '女',
        self::SEX_SECRECY => '保密',
    );

    public $parent_id;      // 父级ID

    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }

    public static function tableName()
    {
        return 'member';
    }
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['grade_id', 'status'], 'integer'],
            [['created_at', 'updated_at'], 'safe'],
            [['password', 'pay_password', 'wx_openid', 'wx_unoin', 'qq_openid'], 'string', 'max' => 32],
            [['idcard', 'username', 'nick'], 'string', 'max' => 18],
            [['realname'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 11],
            [['sex'], 'string', 'max' => 1],
            [['headimg'], 'string', 'max' => 200],
            [['token'], 'string', 'max' => 64],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '用户ID',
            'parent_id' => '上级ID',
            'username' => '用户名',
            'password' => '登陆密码',
            'pay_password' => '支付密码',
            'nick' => '用户昵称',
            'idcard' => '用户身份证',
            'realname' => '真实姓名',
            'mobile' => '手机号',
            'sex' => '用户性别',
            'wx_openid' => '微信OPEN_ID',
            'wx_unoin' => '微信服务号ID',
            'qq_openid' => 'QQOPEN_ID',
            'headimg' => '用户头像',
            'grade_id' => '用户等级',
            'token' => '验证字符串',
            'status' => '状态',
            'created_at' => '注册时间',
            'updated_at' => '更新时间',
        ];
    }

    // 获取用户账号信息
    public function getAccount(){

        if (!MemberAccount::find()->where(['member_id' => $this->id])->exists()) {
            $account     = new MemberAccount();
            $account->user_id = $this->id;
            $account->save(false);
        }

        return $this->hasOne(MemberAccount::className(), ['member_id' => 'id']);
    }

    // 获取用户账号信息
    public function getGradename(){
        return $this->hasOne(Grade::className(), ['id' => 'grade_id']);
    }

    // 获取分销信息
    public function getLeveldis(){
        return $this->hasOne(MemberLevel::className(), ['user_id' => 'id']);
    }

    // 查询用户的所有上级名称
    public function getParentsName(){
        $uids = array();
        if(  $parent = $this->leveldis ){
            $uids = explode(',', $parent->teamids);
            $uids[] = $parent->grandpa;
            $uids[] = $parent->parent;
        }
        array_unique($uids);
        return static::find()->select("username")->where(['id' => $uids])->orderBy('id asc')->asArray()->column();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ONLINE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        Yii::$app->session->id = $token;
        $session = Yii::$app->session;
        if ($id = $session[self::SESSION_KEY]) {
            return static::findIdentity($id);
        }

        return null;
    }

    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ONLINE]);
    }

    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ONLINE,
        ]);
    }

    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) { return false;  }

        $timestamp = (int) substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    public function getId()
    {
        return $this->getPrimaryKey();
    }
    public function getAuthKey()
    {
        return $this->token;
    }
    public function generateAuthKey()
    {
        $this->token = md5(time(). '_' . $this->id );
    }
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }
    public function setPassword($password)
    {
        $this->password = md5(md5('house'. $password));
    }
    public function validatePassword($password)
    {
        return $this->setPassword($password) === $this->password;
    }
    public function setPayPassword($password)
    {
        $this->pay_password = md5(md5('pay'. $password));
    }
    public function validatePayPassword($password)
    {
        return $this->setPayPassword($password) === $this->pay_password;
    }

    // 过滤用户信息
    public static function handData($member, $changeToke = true){

        // 储存用户信息到redis
        RedisHelper::select(2);

        if( $changeToke ){  // 更新token
            $member->token && RedisHelper::delCache( $member->token );
            $member->generateAuthKey();
            $member->save();
        }

        RedisHelper::setCache($member->token, json_encode( $member->toArray(), JSON_UNESCAPED_UNICODE), 30*86400);

        $return  = [];
        $return['id'] = $member->id;
        $return['username'] = $member->username;
        $return['nickname'] = $member->nick;
        $return['sex'] = self::$SEX_MAP[$member->sex];
        $return['token'] = $member->token;
        $return['headimg'] = $member->headimg;
        $return['grade_id'] = $member->grade_id;
        $return['grade'] = Grade::getCacheGrade($member->grade_id);
        $return['mobile'] = $member->mobile;
        $return['created_at'] = date('Y-m-d H:i:s', $member->created_at);
        $return['realname'] = StringHelper::formatUserName($member->realname);
        $return['idcard'] = StringHelper::formatIdentity($member->idcard);

        return $return;
    }
}
