<?php

namespace frontend\behaviors;

use common\models\LandingPage;

/**
 * Trait ControllerHelperLandingPageTrait — Трейт помогает работать с посадочными страницами
 *
 * @property \yii\web\Controller $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait ControllerHelperLandingPageTrait
{
    /**
     * Перед показом вью загрузим все посадочные страницы
     */
    protected function landingPageBeforeViewRender()
    {
        $landingPages = LandingPage::getAllWithCache();
        $landingPages = array_filter($landingPages, function (LandingPage $landingPage) {
            return $landingPage->isVisible();
        });
        $this->owner->getView()->params['landing-pages'] = $landingPages;
    }
}
