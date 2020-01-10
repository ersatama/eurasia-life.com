<?php

return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => require __DIR__ . '/_main-url-manager-rules.php',
    'enableStrictParsing' => false,
    'normalizer' => [
        'class' => 'yii\web\UrlNormalizer',
    ],
];
