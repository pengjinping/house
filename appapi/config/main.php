<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'appapi\controllers',
    'components' => [
        'response'=>[
            'on beforeSend' => function($event){
                $response = $event->sender;
                $response->format = 'json';
                $response->getHeaders()->set('Access-Control-Allow-Origin', '*');
                $response->getHeaders()->set('Access-Control-Allow-Headers', 'accept, x-app-id, cache-control, x-token, x-source, content-type');

                if(is_string($response->data) && strpos($response->data, 'html')){
                    echo htmlspecialchars_decode($response->data); exit;
                }
                if ($response->data !== null) {
                    $data['msg'] =  empty($response->data['message']) ? 'success' : $response->data['message'];
                    $data['msg'] = $response->statusCode != 200  && is_string($response->data) ? $response->data : $data['msg'];
                    $data['code'] = $response->statusCode;
                    $data['data'] = $response->statusCode == 200 ? $response->data : (YII_DEBUG ? $response->data : null);
                    $response->data = $data;
                }
                $response->statusCode = 200;
            }
        ],
        'request' => [
            'csrfParam' => '_csrf-api',
        ],
        'user' => [
            'identityClass' => 'common\models\Member',
        ],
        'session' => [
            'name' => 'advanced-api',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'app/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
    ],
    'params' => $params,
];
