<?php

namespace common\models\product;

use Yii;

class BuildContent extends \yii\db\ActiveRecord
{
    public static function getDb()
    {
        return Yii::$app->get('db_product');
    }
    public static function tableName()
    {
        return 'build_content';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['build_id'], 'integer'],
            [['content'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'       => 'ID',
            'build_id' => '信息ID',
            'content'  => '详细内容',
        ];
    }
}
