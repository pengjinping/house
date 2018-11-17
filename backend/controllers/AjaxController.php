<?php
namespace backend\controllers;

use common\models\Area;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;

class AjaxController extends Controller
{
    public function actionSite($pid, $level=2)
    {
        if($level == 1){
            echo Html::tag('option', "--请选择省--", ['value'=>'empty']) ;
        }else if($level == 2){
            echo Html::tag('option', "--请选择市--", ['value'=>'empty']) ;
        }else{
            echo Html::tag('option', "--请选择区--", ['value'=>'empty']) ;
        }

        $areas = Area::find()
            ->where(['like', 'code', $pid])
            ->andWhere(['level' => $level])
            ->all();

        foreach ($areas as $area){
            echo Html::tag('option', Html::encode($area['name']), ['value'=> $area['code'] ] ) ;
        }
    }
}
