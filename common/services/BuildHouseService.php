<?php

namespace common\services;

use common\models\Area;
use common\models\product\BuildHouse;

/**
 * 新房内容查询筛选
 */
class BuildHouseService
{

    /**
     * @param $query
     * @param $dataProvider
     * @param $params
     */
    public static function handData($dataProvider, $query, $params){

        $query->select(["id", "title", "image", "area_id", "address", "developer", "type", "price", "flag", "rate"]);
        $query->andFilterWhere(['resource' => BuildHouse::RESOURCE_NEW]);
        $query->andFilterWhere(['status' => BuildHouse::STATUS_DISPLAY]);
        $query->andFilterWhere(['area_id' => @$params['area']]);
        $query->andFilterWhere(['type' => @$params['type']]);
        $query->andFilterWhere(['like', 'title', @$params['keyword']]);

        $list = $dataProvider->getModels();
        foreach ($list as &$item){
            $item['type'] = BuildHouse::$TYPE_MAP[$item['type']];
            $item['area'] = Area::getCacheArea(3, $item['area_id']);
        }

        return $list;
    }
}
?>