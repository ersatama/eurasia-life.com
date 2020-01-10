<?php

$domain = 'eurasia-life.test';

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
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
    ],
];
