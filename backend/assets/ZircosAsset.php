<?php

namespace backend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class ZircosAsset – Zircos dashboard template
 * @package backend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ZircosAsset extends AssetBundle
{
    public $basePath = '@webroot/zircos';

    public $baseUrl = '@web/zircos';

    public $css = [
        'css/core.css',
        'css/components.css',
        'css/icons.css',
        'css/pages.css',
        'css/menu.css',
        'css/responsive.css',
        'plugins/nestable/jquery.nestable.css',
    ];

    public $js = [
        [
            'js/modernizr.min.js',
            'position' => View::POS_HEAD,
        ],
        'js/detect.js',
        'js/fastclick.js',
        'js/jquery.blockUI.js',
        'js/waves.js',
        'js/jquery.slimscroll.js',
        'js/jquery.scrollTo.min.js',
        'js/jquery.core.js',
        'js/jquery.app.js',
        'plugins/bootstrap-filestyle/js/bootstrap-filestyle.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
        'yii\bootstrap\BootstrapPluginAsset',
    ];
}
