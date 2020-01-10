<?php

namespace frontend\widgets;

use yii\base\Widget;

/**
 * Class BodyAttrs – Виджет помогает указывать атрибуты для body
 *
 * $view->params['body-attrs']['class'][] = 'class-a';
 * $view->params['body-attrs']['class'][] = 'class-b';
 * $view->params['body-attrs']['class'] = 'main-page';
 * $view->params['body-attrs']['style'] = 'color: red';
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class BodyAttrs extends Widget
{
    /**
     * @var bool – Главная страница
     */
    public $isMainPage = false;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $attrs = [
            $this->getAttrFromParams('body-class', 'class'),
            $this->getAttrFromParams('body-style', 'style', '; '),
        ];

        return $this->result($attrs);
    }

    /**
     * Результат в строку
     * @param array $attrs
     * @return string
     */
    protected function result(array $attrs)
    {
        $attrsString = '';
        foreach ($attrs as $attr) {
            if (!$attr) {
                continue;
            }
            $attrsString .= ' ' . $attr;
        }

        return $attrsString;
    }

    /**
     * Вернет атрибут для body
     * @param $keyName
     * @param $attrName
     * @param string $glue
     * @return null|string
     */
    protected function getAttrFromParams($keyName, $attrName, $glue = ' ')
    {
        $view = $this->getView();
        if (isset($view->params[$keyName]) && $view->params[$keyName]) {
            $classes = is_array($view->params[$keyName]) ? implode($glue, $view->params[$keyName]) : $view->params[$keyName];
            return sprintf('%s="%s"', $attrName, $classes);
        }
        return null;
    }
}
