<?php

namespace frontend\routes;

use Yii;
use yii\base\Component;
use yii\web\UrlRuleInterface;
use common\models\PostArticle;
use common\models\Language;
use frontend\behaviors\ShowHiddenPage;

/**
 * Class Post — Роут Публикаций
 *
 * @mixin ShowHiddenPage
 *
 * @package frontend\routes
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Post extends Component implements UrlRuleInterface
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

        $langSlugs = implode('|', Language::getAllSlugs());

        if (preg_match('/^(' . $langSlugs . ')\/news$/uis', $pathInfo, $matches)) {
            /**
             * @see \frontend\controllers\PostsController::actionIndex()
             */
            return ['posts/index', ['language' => $matches[1]]];
        }

        $postArticle = preg_match('/^(' . $langSlugs . ')\/news\/(\d+).*?$/uis', $pathInfo, $matches)
            ? $this->findPostArticle($matches[1], (int)$matches[2])
            : null;

        /**
         * @see \frontend\controllers\PostsController::actionView()
         */
        return $postArticle ? ['posts/view', ['postArticle' => $postArticle]] : false;
    }

    /**
     * @inheritdoc
     */
    public function createUrl($manager, $route, $params)
    {
        if ($route === 'posts/index') {
            $url = sprintf('/%s/news', $params['language'] ?? Yii::$app->language);

            if (isset($params['language'])) {
                unset($params['language']);
            }

            if ($params) {
                $url .= '?' . http_build_query($params);
            }

            return $url;
        }

        if ($route === 'posts/view') {
            foreach ($params as $param) {
                if ($param instanceof PostArticle) {
                    $return = '/';
                    $return .= Language::getSlugById($param->lang_id);
                    $return .= '/news/';
                    $return .= $param->id;
                    if (($t = $param->slug)) {
                        $return .= '-' . $t;
                    }
                    return $return;
                }
            }
        }

        return false;
    }

    /**
     * Найдет публикацию
     * @param string $langSlug
     * @param int $postArticleId
     * @return PostArticle|null
     */
    protected function findPostArticle(string $langSlug, int $postArticleId): ?PostArticle
    {
        $query = PostArticle::find();
        $query->active();
        if (!$this->showHiddenPage()) {
            $query->visible();
            $query->published();
        }
        $query->andWhere(['lang_id' => Language::getIdBySlug($langSlug)]);
        $query->id($postArticleId);
        $query->with(['mainImage', 'socImage']);
        $query->limit(1);

        $postArticle = $query->one();

        return $postArticle;
    }
}