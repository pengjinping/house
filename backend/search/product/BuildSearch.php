<?php

namespace backend\search\product;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\product\BuildHouse;

class BuildSearch extends BuildHouse
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['title', 'area_id', 'village', 'developer', 'type', 'resource', 'status'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    public function search($params, $source)
    {
        $query = BuildHouse::find()->where([ 'resource' => $source]);
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

        $query->andFilterWhere([
            'id' => $this->id,
            'status' => $this->status,
            'type' => $this->type
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'area_id', $this->area_id])
            ->andFilterWhere(['like', 'village', $this->village])
            ->andFilterWhere(['like', 'developer', $this->developer]);

        return $dataProvider;
    }
}
