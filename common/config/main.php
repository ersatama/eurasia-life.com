<?php

return [
    'language' => 'ru',
    'timeZone' => 'Asia/Almaty',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'commonCache' => [
            'class' => 'yii\caching\FileCache',
            'cachePath' => '@console/runtime/cache-common',
            'fileMode' => 0777,
            'dirMode' => 0777,
        ],
        'fileStorage' => [
            'class' => 'common\components\FileStorage',
            'modelClassIdMap' => [
                'common\models\Page' => 1,
                'common\models\ShortCode' => 10,
                'common\models\PostArticle' => 100,
            ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'common\base\FileTarget',
                    'enableRotation' => true,
                    'logFile' => '@runtime/logs/app-' . date('Y-m-d') . '.log',
                    'levels' => ['error', 'warning'],
                    'except' => ['redirects']
                ],
                [
                    'class' => 'common\base\FileTarget',
                    'enableRotation' => true,
                    'logFile' => '@runtime/logs/redirects-' . date('Y-m-d') . '.log',
                    'levels' => ['error', 'warning'],
                    'categories' => ['redirects']
                ],
            ],
        ],
        'sphinx' => [
            'class' => 'yii\sphinx\Connection',
            'dsn' => 'mysql:host=127.0.0.1;port=9306;',
            'username' => '',
            'password' => '',
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm' => '@vendor/npm-asset',
    ],
];
