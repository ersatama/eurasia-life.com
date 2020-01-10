<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class VueMaskAsset
 * @package frontend\assets
 * @author Pavel Oparin <pasha.oparin@gmail.com>
 */
class VueMaskAsset extends AssetBundle
{

    public $js = [
        '//cdn.jsdelivr.net/npm/v-mask/dist/v-mask.min.js',
    ];

    public $jsOptions = [
        'integrity' => 'sha384-HgMUU9Y27A3sYLs51K5l8C5imZoBQNWWDrIDjaGZCDOsztvY+2xSqOnryxX+wOyX',
        'crossorigin' => 'anonymous',
    ];

    public $depends = [
        'vendor',
    ];
}
