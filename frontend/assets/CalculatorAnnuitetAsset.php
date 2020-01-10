<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Class CalculatorAnuitetAsset – Подключаем калькулятор Аннуитетного страхования
 * @package frontend\assets
 * @author Pavel Oparin <pasha.oparin@gmail.com>
 */
class CalculatorAnnuitetAsset extends AssetBundle
{
    public $sourcePath = '@app/../gulp/runtime/dist';

//    public $css = [
//        'css/styles.css',
//    ];

    public $js = [
        'js/calc-annuitet.js',
    ];

    public $depends = [
        AppAsset::class,
    ];
}
