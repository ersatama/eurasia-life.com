<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class Select2Asset
 *
 * @package backend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Select2Asset extends AssetBundle
{
    public $baseUrl = '@web/zircos/plugins/select2';

    public $css = [
        'css/select2.min.css',
    ];

    public $js = [
        'js/select2.min.js',
    ];

    public $depends = [
        'backend\assets\ZircosAsset',
    ];
}
