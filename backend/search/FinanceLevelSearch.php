<?php

namespace backend\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\finance\FinanceLevel;

class FinanceLevelSearch extends FinanceLevel
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'min', 'max'], 'integer'],
            [['title', 'status'], 'safe'],
            [['channle', 'operate', 'user', 'parent', 'grandpa', 'league', 'team'], 'number'],
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
        $query = FinanceLevel::find();
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
            'min' => $this->min,
            'max' => $this->max,
            'channle' => $this->channle,
            'operate' => $this->operate,
            'user' => $this->user,
            'parent' => $this->parent,
            'grandpa' => $this->grandpa,
            'league' => $this->league,
            'team' => $this->team,
        ]);

        $query->andFilterWhere(['like', 'title', $this->title])
            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
