<?php

/**
 * Настройка ассетов
 */
// @todo: check
return [
    'vendor' => [
        'class' => 'yii\web\AssetBundle',
        'sourcePath' => '@app/../gulp/runtime/dist',
        'css' => [
        ],
        'js' => [
            'js/vendor.js',
        ],
    ],
    'frontend\assets\AppAsset' => [
        'depends' => [
            'common\assets\IE9Asset',
            'vendor',
//            'frontend\assets\VueMaskAsset',
        ],
    ],
//    'frontend\assets\VueMaskAsset' => [
//        'sourcePath' => null,
//        'js' => [],
//        'jsOptions' => [],
//        'depends' => [
//        ],
//    ],
    'yii\web\JqueryAsset' => [
        'sourcePath' => null,
        'js' => [],
        'depends' => [
            'vendor',
        ],
    ],
    'yii\bootstrap\BootstrapAsset' => [
        'sourcePath' => null,
        'css' => [],
    ],
    'yii\bootstrap\BootstrapPluginAsset' => [
        'sourcePath' => null,
        'js' => [],
    ],
    'yii\web\YiiAsset' => [
        'sourcePath' => null,
        'js' => [],
    ],
    'yii\widgets\ActiveFormAsset' => [
        'sourcePath' => null,
        'js' => [],
    ],
    'yii\validators\ValidationAsset' => [
        'sourcePath' => null,
        'js' => [],
    ],
];
