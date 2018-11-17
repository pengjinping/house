<?php

namespace backend\search\member;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Member\MemberCoupon;

class CouponMemberSearch extends MemberCoupon
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id', 'coupon_id', 'status'], 'integer'],
            [[ 'pickup_scene'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = MemberCoupon::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'defaultPageSize' => 10
            ],
            'sort' => [
                'defaultOrder' => [
                    'id'=> SORT_DESC
                ]
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'coupon_id' => $this->coupon_id,
            'status' => $this->status,
        ]);
        $query->andFilterWhere(['like', 'pickup_scene', $this->pickup_scene]);

        return $dataProvider;
    }
}
