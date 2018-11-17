<?php
namespace appapi\controllers;

use Yii;
use yii\web\NotFoundHttpException;

use common\models\Area;
use common\models\product\Ads;

/**
 * APP 提供初始化相关数据 【无需登录与参数校验】
 * Class AppController
 * @package appapi\controllers
 */
class AppController extends BaseController
{
    public $checkAuth = false;  #无需验证登录

    /*
     * 初始化数据信息
     */
    public function actionInit(){
        $data['APP_TOKEN'] = md5('education');
        $data['version'] = 'V0.00.001';
        $data['timeout'] = time();
        return $data;
    }

    /**
     * 获取区域列表
     * 【后期考虑前端控件 不再使用该方式】
     * @return array
     */
    public function actionArea(){
        return Area::find()->select("code,name")->where(['level' => 3])->asArray()->cache(300)->all();
    }

    /**
     * 返回广告位信息
     * @return array
     */
    public function actionAds(){
        return Ads::find()->select("id,title as name,url,image as img,type")
                ->where(['status' => Ads::STATUS_ON])
                ->orderBy('num asc')
                ->asArray()->cache(300)->all();
    }

    // 数据过滤DEMO
    public function actionValidata(){
        $input['name'] = 'kingsoft<meta http-equiv="refresh" content="5;">';
        $input['age'] = 100;
        $input['type'] = "1'%22+onmouseover=alert()+d='%22";
        $input['date'] = '2016-05-31 12:26:12"><script>alert(document.cookie)</script><!-';
        $input['email'] = 'pengjinping@163.com';

        echo '原始数据: ';
        print_r($input);

        $this->validata($input, array(
            'name' => 'required|string|size:8',
            'age' => 'min:5|max:101',
            'email' => 'email',
            'type' => 'in:0,1',
            'date' => 'date'
        ));

        echo '数据过滤验证后数据: ';
        print_r($input);
    }

    // 错误 地址不存在
    public function actionError(){
        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            $exception = new NotFoundHttpException('页面不存在');
        }

        return $exception->getMessage();
    }

}
