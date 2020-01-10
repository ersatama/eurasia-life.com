<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class ShareAsset – Подключаем Поделяшки от ilyabirman
 * @package frontend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShareAsset extends AssetBundle
{
    public $sourcePath = '@npm/ilyabirman-likely/release';

    public $css = [
        'likely.css',
    ];

    public $js = [
        'likely.js',
    ];
}
