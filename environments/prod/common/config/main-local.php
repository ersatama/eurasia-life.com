<?php

$domain = 'eurasia-life.com';

return [
    'aliases' => [
        '@backendWeb' => '//admin.' . $domain,
        '@backendWebroot' => '@backend/web',
        '@frontendWeb' => '//' . $domain,
        '@frontendWebroot' => '@frontend/web',
    ],
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=eurasia-life',
            'username' => 'eurasia-life',
            'password' => '',
            'charset' => 'utf8',

            // кэш схемы
            'enableSchemaCache' => true,
            'schemaCacheDuration' => 0, // сбросить вручную по необходимости!
            'schemaCache' => 'commonCache', // чтоб можно было сбросить везде (фронт/бэк/консоль)
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
        ],
    ],
];
