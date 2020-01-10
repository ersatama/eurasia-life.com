<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class FotoramaAsset – Подкючаем Фотораму
 * @package frontend\assets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FotoramaAsset extends AssetBundle
{
    public $sourcePath = '@bower/fotorama';

    public $css = [
        'fotorama.css',
    ];

    public $js = [
        'fotorama.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}
