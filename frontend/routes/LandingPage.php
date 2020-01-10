<?php

namespace frontend\routes;

use yii\base\Component;
use yii\web\UrlRuleInterface;
use common\models\LandingPage as LandingPageModel;
use common\models\Language;
use frontend\behaviors\ShowHiddenPage;

/**
 * Class LandingPage — Роут Посадочных страниц
 *
 * @mixin ShowHiddenPage
 *
 * @package frontend\routes
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LandingPage extends Component implements UrlRuleInterface
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ShowHiddenPage::class,
        ];
    }

    /**
     * @inheritdoc
     * @throws \yii\base\InvalidConfigException
     */
    public function parseRequest($manager, $request)
    {
        $pathInfo = $request->getPathInfo();

        $slugs = implode('|', Language::getAllSlugs());

        $landingPage = preg_match('/^(' . $slugs . ')\/(.*?)$/s', $pathInfo, $matches)
            ? $this->findLandingPage($matches[1], $matches[2])
            : null;

        /**
         * @see \frontend\controllers\LandingPagesController::actionView()
         */
        return $landingPage ? ['landing-pages/view', ['landingPage' => $landingPage]] : false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'landing-pages/view') {
            foreach ($params as $param) {
                if ($param instanceof LandingPageModel) {
                    return sprintf(
                        '/%s/%s',
                        Language::getSlugById($param->lang_id),
                        $param->slug
                    );
                }
            }
        }

        return false;
    }

    /**
     * Найдет посадочную страницу
     * @param string $langSlug
     * @param string $landingPageSlug
     * @return LandingPageModel|null
     */
    protected function findLandingPage(string $langSlug, string $landingPageSlug): ?LandingPageModel
    {
        $langId = Language::getIdBySlug($langSlug);

        foreach (LandingPageModel::getAllWithCache() as $landingPage) {
            if ($landingPage->slug == $landingPageSlug && $landingPage->lang_id == $langId && ($landingPage->isVisible() || $this->showHiddenPage())) {
                return $landingPage;
            }
        }

        return null;
    }
}