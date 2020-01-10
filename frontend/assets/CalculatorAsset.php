<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class CalculatorAsset – Подключаем калькулятор ОСНС
 * @package frontend\assets
 * @author Pavel Oparin <pasha.oparin@gmail.com>
 */
class CalculatorAsset extends AssetBundle
{
    public $sourcePath = '@app/../gulp/runtime/dist';

//    public $css = [
//        'css/styles.css',
//    ];

    public $js = [
        'js/calculator.js',
    ];

    public $depends = [
        MultiselectAsset::class,
    ];
}
