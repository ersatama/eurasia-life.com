<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'name' => 'eurasia-life.com (backend)',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'bootstrap' => ['log'],
    'modules' => [],
    'components' => [
        'request' => [
            'csrfParam' => 'csrf-backend',
            // по-умолчанию чтоб не потерять
            'csrfCookie' => [
                'httpOnly' => true,
            ],
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'on beforeLogin' => function ($event) {
                /**
                 * @var $user \common\models\User
                 */
                $user = $event->identity;
                $user->updateLoginAt();
            },
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'sid-backend',
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
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'enableStrictParsing' => false,
            'normalizer' => [
                'class' => 'yii\web\UrlNormalizer',
            ],
            'rules' => [
                '<url:files/.*>' => 'site/files',
            ],
        ],
        'frontendUrlManager' => require __DIR__ . '/../../frontend/config/_main-url-manager.php',
        'assetManager' => [
            'linkAssets' => true,
            'appendTimestamp' => true,
        ],
    ],
    'as beforeRequest' => [
        'class' => 'yii\filters\AccessControl',
        'rules' => [
            // разрешаем гостям: вход, страницу ошибок
            [
                'allow' => true,
                'controllers' => [
                    'site',
                ],
                'actions' => [
                    'login',
                    'error',
                ],
                'roles' => [
                    '?',
                ],
            ],
            // остальное разрешаем только авторизованным
            [
                'allow' => true,
                'roles' => ['@'],
            ],
        ],
    ],
    'params' => $params,
];
