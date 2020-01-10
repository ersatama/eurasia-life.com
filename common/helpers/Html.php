<?php

namespace common\helpers;

use Yii;
use yii\helpers\Html as BaseHtml;
use yii\web\View;

/**
 * Class Html - переназначим базовый, чтоб сделать по себя
 *
 * @package common\helpers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Html extends BaseHtml
{
    /**
     * @inheritdoc
     */
    public static function encode($content, $doubleEncode = true)
    {
        $r = html_entity_decode('&#65279;'); // '﻿'; @see http://www.fileformat.info/info/unicode/char/feff/index.htm

        $content = str_replace($r, '', $content);

        $content = html_entity_decode($content);

        return parent::encode($content, $doubleEncode);
    }

    /**
     * Тоже самое что и encode(), но готовим для title
     * @param $content
     * @param bool $doubleEncode
     * @return string
     */
    public static function encodeTitle($content, $doubleEncode = true)
    {
        return static::encode($content, $doubleEncode);
    }

    /**
     * @param $num
     * @param $v1
     * @param $v2
     * @param null $v5
     * @return null
     */
    public static function ruEnding($num, $v1, $v2, $v5 = null)
    {
        $mod = $num % 10;
        $cond = floor(($num % 100) / 10) != 1;
        if ($mod == 1 && $cond)
            return $v1;
        if ($mod >= 2 && $mod <= 4 && $cond || $v5 === null)
            return $v2;
        return $v5;
    }

    /**
     * Получаем slug строки для url
     * @param $string
     * @return string
     */
    public static function slug($string)
    {
        $iso9_table = [
            'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Ѓ' => 'G',
            'Ґ' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'YO', 'Є' => 'YE',
            'Ж' => 'ZH', 'З' => 'Z', 'Ѕ' => 'Z', 'И' => 'I', 'Й' => 'J',
            'Ј' => 'J', 'І' => 'I', 'Ї' => 'YI', 'К' => 'K', 'Ќ' => 'K',
            'Л' => 'L', 'Љ' => 'L', 'М' => 'M', 'Н' => 'N', 'Њ' => 'N',
            'О' => 'O', 'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T',
            'У' => 'U', 'Ў' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'TS',
            'Ч' => 'CH', 'Џ' => 'DH', 'Ш' => 'SH', 'Щ' => 'SHH', 'Ъ' => '',
            'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'YU', 'Я' => 'YA',
            'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'ѓ' => 'g',
            'ґ' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'є' => 'ye',
            'ж' => 'zh', 'з' => 'z', 'ѕ' => 'z', 'и' => 'i', 'й' => 'j',
            'ј' => 'j', 'і' => 'i', 'ї' => 'yi', 'к' => 'k', 'ќ' => 'k',
            'л' => 'l', 'љ' => 'l', 'м' => 'm', 'н' => 'n', 'њ' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't',
            'у' => 'u', 'ў' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
            'ч' => 'ch', 'џ' => 'dh', 'ш' => 'sh', 'щ' => 'shh', 'ъ' => '',
            'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu', 'я' => 'ya',
        ];

        // kz
        $iso9_table = array_merge($iso9_table, [
            'Ә' => 'A',
            'ә' => 'a',
//            'І' => 'I', ?
//            'і' => 'i', ?
            'Ң' => 'N',
            'ң' => 'n',
            'Ғ' => 'G',
            'ғ' => 'g',
            'Ү' => 'U',
            'ү' => 'u',
            'Ұ' => 'U',
            'ұ' => 'u',
            'Қ' => 'K',
            'қ' => 'k',
            'Ө' => 'O',
            'ө' => 'o',
            'Һ' => 'H',
            'һ' => 'h',
        ]);

        $string = html_entity_decode($string);

        $string = strtr($string, $iso9_table);
        $string = preg_replace("/[^A-Za-z0-9_\-]/", '-', $string);
        $string = strtolower($string);
        $string = preg_replace("/(-){2,}/", "-", $string);
        $string = trim($string, '/-');
        return $string;
    }

    /**
     * @var string
     */
    protected static $backgroundImages = '';

    /**
     * Помогает ставить фоновые картинки для блоков
     * @param $selector
     * @param $imageUrl
     */
    public static function backgroundImage($selector, $imageUrl)
    {
        if (!static::$backgroundImages) {
            $view = Yii::$app->view;
            $view->on(View::EVENT_BEGIN_PAGE, function () use ($view) {
                $view->registerCss(static::$backgroundImages);
            });
        }

        static::$backgroundImages .= "$selector{background-image: url('$imageUrl');}";
    }

    /**
     * Поле для номера телефона
     * @param $model
     * @param $attribute
     * @param array $options
     * @return string
     * @throws \Exception
     */
    public static function activeInputTel($model, $attribute, $options = [])
    {
        if (!isset($options['placeholder']) && !array_key_exists('placeholder', $options)) {
            $options['placeholder'] = 'Телефон';
        }
        return static::activeInput('tel', $model, $attribute, $options);
    }

    /**
     * Вернет красиво цену
     * @param int $price
     * @param null|string $sep
     * @return string
     */
    public static function price($price, $sep = null)
    {
        if (!$price) {
            return $price;
        }
        $result = number_format($price, 0, '.', ' ');
        if ($sep !== null) {
            $result = str_replace(' ', $sep, $result);
        }
        return $result;
    }

    /**
     * @param $price
     * @param null $sep
     * @return string
     */
    public static function numberFormat($price, $sep = null)
    {
        return static::price($price, $sep);
    }

    /**
     * Вернет красиво цену
     * - разделитель nbsp;
     * @param int $price
     * @return string
     */
    public static function priceNbsp($price)
    {
        return static::price($price, '&nbsp;');
    }

    /**
     * Вернет номер телефона в виде ссылки
     * @param $phone
     * @return string
     */
    public static function phone($phone, $options = [])
    {
        return self::a($phone, 'tel:' . preg_replace('/[^0-9+]/', '', $phone), $options);
    }

    /**
     * Вернет горизонтальный список
     * @param $list
     * @param string $ulClass
     * @return string
     */
    public static function inlineList($list, $ulClass = '')
    {
        $return = [];
        foreach ($list as $l) {
            $return[] = '<li class="list-inline-item">' . $l . '</li>';
        }
        return $return ? '<ul class="list-inline' . ($ulClass ? ' ' . $ulClass : '') . '">' . implode('', $return) . '</ul>' : '';
    }

    /**
     * Вернет горизонтальный список ссылок
     * @param $list
     * @return string
     */
    public static function inlineListLinks($list)
    {
        $result = [];
        foreach ($list as $key => $value) {
            $result[] = Html::a($value, $key);
        }
        return self::inlineList($result);
    }

    /**
     * Покажем красиво дату
     * @param int $at
     * @return string
     */
    public static function dateString(int $at): string
    {
        $return = date('d', $at);

        $map = [
            1 => 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря'
        ];

        $return .= ' ' . $map[date('n', $at)];

        if (date('Y') != ($year = date('Y', $at))) {
            $return .= ' ' . $year;
        }

        return $return;
    }
}