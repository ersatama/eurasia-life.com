<?php

namespace common\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Class IE9Asset – Скрипты для IE9 и ниже
 * @package common\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class IE9Asset extends AssetBundle
{
    public $jsOptions = [
        'condition' => 'lt IE9',
        'position' => View::POS_HEAD
    ];

    // @todo: npm?
    public $js = [
        '//oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js',
        '//oss.maxcdn.com/respond/1.4.2/respond.min.js',
    ];
}
