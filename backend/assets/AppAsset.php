<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset – Main backend application asset bundle.
 * @package backend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';

    public $baseUrl = '@web';

    public $css = [
        'css/site.css',
    ];

    public $js = [
        'js/site.js',
    ];

    public $depends = [
        'common\assets\IE9Asset',
        'backend\assets\ZircosAsset',
    ];
}
