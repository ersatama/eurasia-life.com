<?php

namespace frontend\widgets;

/**
 * Class MainMenu – Виджет главного меню
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class MainMenu extends \yii\widgets\Menu
{
    public $options = [
        'class' => 'main-nav__items',
        'itemscope' => '',
        'itemtype' => 'http://www.schema.org/SiteNavigationElement',
    ];

    public $itemOptions = [
        'class' => 'main-nav__item',
        'itemprop' => 'name',
    ];

    public $linkTemplate = '<a href="{url}" class="main-nav__link" itemprop="url"><span class="main-nav__title">{label}</span></a>';

    public $activeCssClass = 'main-nav__item_active';

    public $encodeLabels = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        ob_start();
        parent::run();
        $content = ob_get_clean();

        $content && printf('<nav class="main-nav">%s</nav>', $content);
    }
}
