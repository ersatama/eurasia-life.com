<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/../gulp/runtime/dist';

    public $css = [
        'css/styles.css',
    ];

    public $js = [
        'js/app.js',
    ];

    public $depends = [
        'common\assets\IE9Asset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
