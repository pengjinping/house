<?php

namespace common\models\Member;

use Yii;
use yii\web\BadRequestHttpException;

class MemberFav extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_member');
    }
    public static function tableName()
    {
        return 'member_fav';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'value', 'type'], 'integer'],
            [['created_at'], 'safe'],
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
            'type'       => '类型',
            'value'     => '类型ID值',
            'created_at'    => '创建时间',
        ];
    }

    /**
     *  判断用户是否关注过该资源
     * @param $uid
     * @param $type
     * @param $value
     */
    public static function isExists($uid, $type, $value){
        return static::find()->where(['user_id' => $uid])->andWhere(['type' => $type])->andWhere(['value' => $value])->count();
    }

}
