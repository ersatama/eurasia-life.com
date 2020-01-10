<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class MultiselectAsset – Подключаем компонент vue
 * @package frontend\assets
 * @author Pavel Oparin <pasha.oparin@gmail.com>
 */
class MultiselectAsset extends AssetBundle
{
    public $sourcePath = '@npm/vue-multiselect/dist';

    public $css = [
        'vue-multiselect.min.css',
    ];

    public $js = [
        'vue-multiselect.min.js',
    ];

    public $depends = [
        'vendor'
    ];
}
