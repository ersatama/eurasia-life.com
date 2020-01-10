<?php

namespace frontend\widgets;

use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Class LinkPager – Пагинация
 * - bootstrap 4
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LinkPager extends \yii\widgets\LinkPager
{
    public $prevPageLabel = false;

    public $nextPageLabel = false;

    public $linkContainerOptions = [
        'class' => 'page-item',
    ];

    public $linkOptions = [
        'class' => 'page-link',
    ];

    /**
     * @var array
     */
    public $paginationWrapperOptions = [];

    /**
     * @inheritdoc
     */
    public function run()
    {
        ob_start();
        parent::run();
        $return = ob_get_clean();
        if (!$return) {
            return '';
        }

        $options = $this->paginationWrapperOptions;
        $tag = ArrayHelper::remove($options, 'tag', 'nav');

        $options['aria-label'] = 'Пагинация';

        echo Html::tag($tag, $return, $options);
    }
}
