<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Class TimepickerAsset
 *
 * @package backend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class TimepickerAsset extends AssetBundle
{
    public $baseUrl = '@web/zircos/plugins/timepicker';

    public $css = [
        'bootstrap-timepicker.min.css',
    ];

    public $js = [
        'bootstrap-timepicker.js',
    ];

    public $depends = [
        'backend\assets\ZircosAsset',
    ];
}