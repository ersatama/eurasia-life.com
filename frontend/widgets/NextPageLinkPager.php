<?php

namespace frontend\widgets;

use yii\helpers\Html;

/**
 * Class NextPageLinkPager – Пагинация одной кнопкой "Дальше"
 *
 * @package frontend\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class NextPageLinkPager extends LinkPager
{
    /**
     * @inheritdoc
     */
    public $nextPageLabel = 'дальше';

    /**
     * @inheritdoc
     */
    public $paginationWrapperOptions = [
        'class' => 'text-center pb-5'
    ];

    /**
     * @inheritdoc
     */
    public $linkOptions = [
        'class' => 'btn btn_a next-link',
    ];

    /**
     * @var bool
     */
    public $registerLinkTags = true;

    /**
     * @inheritdoc
     */
    protected function renderPageButtons()
    {
        $pageCount = $this->pagination->getPageCount();
        if ($pageCount < 2 && $this->hideOnSinglePage) {
            return '';
        }

        $currentPage = $this->pagination->getPage();

        // next page
        if ($this->nextPageLabel !== false) {
            if (($page = $currentPage + 1) >= $pageCount - 1) {
                $page = $pageCount - 1;
            }

            if ($currentPage >= $pageCount - 1) {
                return '';
            }

            $button = $this->renderPageButton(
                $this->nextPageLabel,
                $page,
                $this->nextPageCssClass,
                $currentPage >= $pageCount - 1,
                false
            );

            return $button;
        }

        return '';
    }

    /**
     * @inheritdoc
     */
    protected function renderPageButton($label, $page, $class, $disabled, $active)
    {
        $linkOptions = $this->linkOptions;
        $linkOptions['data-page'] = $page;

        return Html::a($label, $this->pagination->createUrl($page), $linkOptions);
    }
}
