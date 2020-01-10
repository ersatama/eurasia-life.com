<?php

namespace frontend\widgets;

use yii\widgets\Breadcrumbs as BaseBreadcrumbs;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class Breadcrumbs – Хлебные крошки
 * - делаем под bootstrap 4
 * - микроразметка
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Breadcrumbs extends BaseBreadcrumbs
{
    public $tag = 'ol';

    public $options = ['class' => 'breadcrumb', 'itemscope' => true, 'itemtype' => 'http://schema.org/BreadcrumbList'];

    public $itemTemplate = '<li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">{link}</li>' . PHP_EOL;

    public $activeItemTemplate = '<li class="breadcrumb-item active" aria-current="page" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">{link}</li>' . PHP_EOL;

    /**
     * @inheritdoc
     */
    public function run()
    {
        ob_start();
        parent::run();
        $return = ob_get_clean();

        echo $return ? sprintf('<nav aria-label="breadcrumb" class="breadcrumb-nav mb-3">%s</nav>', $return) : '';
    }

    /**
     * @inheritdoc
     */
    protected function renderItem($link, $template)
    {
        $encodeLabel = ArrayHelper::remove($link, 'encode', $this->encodeLabels);
        if (array_key_exists('label', $link)) {
            $label = $encodeLabel ? Html::encode($link['label']) : $link['label'];
        } else {
            throw new InvalidConfigException('The "label" element is required for each link.');
        }
        if (isset($link['template'])) {
            $template = $link['template'];
        }
        if (isset($link['url'])) {
            $options = $link;
            unset($options['template'], $options['label'], $options['url']);
            $options['itemprop'] = 'item';
            $link = Html::a(sprintf('<span itemprop="name">%s</span></a>', $label), $link['url'], $options);
            $link .= '<meta itemprop="position" content="' . $this->position() . '" />';
        } else {
            $link = $label;
            $template = '<li class="breadcrumb-item active" aria-current="page">{link}</li>';
        }

        return strtr($template, ['{link}' => $link]);
    }

    /**
     * @var int
     */
    protected $position = 0;

    /**
     * @return int
     */
    protected function position()
    {
        return ++$this->position;
    }
}