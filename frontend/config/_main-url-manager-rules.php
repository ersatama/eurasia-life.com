<?php

/**
 * Роутинг сайта
 *
 * @todo: проверить
 */

// не забываем про robots.txt

return [
    // Главная
    [
        'name' => '/',
        'pattern' => '/<language:(ru|kz|en)>',
        'route' => 'site/index',
        'defaults' => [
            'language' => 'ru',
        ],
    ],

    // Нарезка картинок
    '<path:resized/.*>' => 'site/resized',

    // sitemap.xml
    [
        'pattern' => 'sitemap',
        'route' => 'sitemap/default/index',
        'suffix' => '.xml'
    ],

    // rss
    'rss' => 'site/rss',

//    // Ошибка 500
//    '/ups' => 'site/ups',

    // Посадочные страницы
    [
        'class' => frontend\routes\LandingPage::class,
    ],

    // Посты
    [
        'class' => frontend\routes\Post::class,
    ],

    // Контент-страницы
    [
        'class' => frontend\routes\Page::class,
    ],
];
