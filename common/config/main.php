<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'language'   => 'zh-CN',
    'timeZone'   => 'Asia/Chongqing',
   // 'bootstrap' => ['queue'],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        /*'queue' => [
            'class' => \yii\queue\redis\Queue::class,
            'as log' => \yii\queue\LogBehavior::class,
        ],*/
    ],
];
