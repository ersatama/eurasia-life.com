<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'name' => 'eurasia-life.com (frontend)',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'components' => [
        'request' => [
            'csrfParam' => 'csrf',
            // по-умолчанию чтоб не потерять
            'csrfCookie' => [
                'httpOnly' => true,
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-frontend', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the frontend
            'name' => 'sid',
            // по-умолчанию чтоб не потерять
            'cookieParams' => [
                'httpOnly' => true,
            ],
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
            'errorAction' => 'site/error',
        ],
        'urlManager' => require __DIR__ . '/_main-url-manager.php',
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
            'bundles' => require __DIR__ . '/_main-asset-manager-bundles.php',
        ],
    ],
    'modules' => [
        'sitemap' => require '_main-sitemap-module.php',
    ],
    'params' => $params,
    'as beforeRequest' => [
        'class' => \frontend\behaviors\Language::class,
    ],
];
