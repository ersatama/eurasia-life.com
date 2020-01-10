<?php

namespace frontend\behaviors;

use common\models\Page;

/**
 * Trait ControllerHelperPageTrait — Трейт помогает работать с контент-страницами
 *
 * @property \yii\web\Controller $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait ControllerHelperPageTrait
{
    /**
     * @return Page[]
     */
    public function getAllPages()
    {
        return array_filter(Page::getAllPages(), function (Page $page) {
            return $page->visible;
        });
    }

    /**
     * Перед показом вью загрузим Страницы
     */
    protected function pageBeforeViewRender()
    {
        $view = $this->owner->getView();
        $pages = $this->getAllPages();

        $langRootPage = null;
        $currentLanguage = \Yii::$app->language;
        foreach ($pages as $page) {
            if ($page->depth == 1 && $page->slug === $currentLanguage) {
                $langRootPage = $page;
                break;
            }
        }

        $main = [];
        if ($langRootPage) {
            foreach ($pages as $page) {
                if ($page->depth == 2 && $page->isChildOf($langRootPage)) {
                    $main[] = $page;
                }
            }
        }

        $view->params['pages-main'] = $main;
        $view->params['pages'] = $pages;
    }
}
