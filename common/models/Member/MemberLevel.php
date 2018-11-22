<?php

namespace common\models\Member;

use Yii;
use yii\web\BadRequestHttpException;

class MemberLevel extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'member_level';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'user_level', 'parent', 'grandpa'], 'integer'],
            [['teamids'], 'string', 'max' => 500],
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
            'user_level' => '用户级别',
            'parent'     => '父级ID',
            'grandpa'    => '二级父级ID',
            'teamids'    => '团队管理用户',
        ];
    }

    // 添加新用户
    public static function addOneSave($uid, $parent_id){
        $parents = static::findOne(['user_id' => $parent_id]);
        if($parents == null){
            throw new BadRequestHttpException('父级用户不存在');
        }
        $userInfo = static::findOne(['user_id' => $uid]);
        if($userInfo == null){
            $userInfo = new static();
        }

        $userInfo->user_id = $uid;
        $userInfo->user_level = $parents->user_level + 1;
        $userInfo->parent = $parent_id;
        $userInfo->grandpa = $parents->parent;

        $teamids = explode(',', $parents->teamids);
        $teamids[] = $parents->grandpa;
        foreach ($teamids as $k => $item){
            if($item == 0) unset($teamids[$k]);
        }
        $userInfo->teamids = join(',', $teamids);
        if( !$userInfo->save() ){
            throw new BadRequestHttpException('保存用户等级失败'.$userInfo->errors[0] );
        }

        // 循环改变子元素的等级
        $childrens = static::findAll(['parent' => $uid]);
        foreach ($childrens as $child){
            static::addOneSave($child->user_id, $child->parent);
        }
    }

}
