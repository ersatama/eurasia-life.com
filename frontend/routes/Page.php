<?php

namespace frontend\routes;

use yii\base\Component;
use yii\helpers\Url;
use yii\web\UrlRuleInterface;
use common\models\Page as PageModel;
use common\models\PageQuery;
use frontend\behaviors\ShowHiddenPage;

/**
 * Class Post — Роут Контент-страниц
 *
 * @mixin ShowHiddenPage
 *
 * @package frontend\routes
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Page extends Component implements UrlRuleInterface
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

        $page = $this->findPageBySlug($pathInfo);

        /**
         * @see \frontend\controllers\PagesController::actionView()
         */
        return $page ? ['pages/view', ['page' => $page]] : false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'pages/view') {
            return $this->createUrlPagesView($params);
        }

        return false;
    }

    /**
     * @param array $params
     * @return string
     */
    protected function createUrlPagesView(array $params): string
    {
        return Url::to(['site/index', 'language' => ($params['language'] ?? null)]);
    }

    /**
     * Найдет контент-страницу по УРЛ
     * @param string $slug
     * @return PageModel|null
     */
    protected function findPageBySlug(string $slug): ?PageModel
    {
        $query = PageModel::find();
        $query->active();
        $query->visibleCheckIf(!$this->showHiddenPage());
        $query->fullSlug($slug, function (PageQuery $query) {
            $query->active();
            $query->visibleCheckIf(!$this->showHiddenPage());
            $query->limit(1);
        });
        $query->limit(1);
        $page = $query->one();

        return $page;
    }
}