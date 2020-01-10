<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Language;
use common\models\PostArticle;
use frontend\behaviors\ControllerHelper;
use frontend\behaviors\RequestForm;
use frontend\behaviors\ShowHiddenPage;
use frontend\models\PostArticleSearchForm;

/**
 * Class PostsController – Публикации на сайте
 *
 * @mixin ControllerHelper
 * @mixin RequestForm
 * @mixin ShowHiddenPage
 *
 * @see \frontend\routes\Post
 *
 * @package frontend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PostsController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            RequestForm::class,
            ShowHiddenPage::class,
        ];
    }

    /**
     * Список новостей
     * @see \frontend\routes\Post::parseRequest()
     * @param string $language
     * @return string
     */
    public function actionIndex(string $language)
    {
        $postArticleSearchForm = new PostArticleSearchForm(['language' => $language]);
        $postArticleSearchForm->search();
        $postArticleSearchForm->dataProvider->models; // запрос тут

        return $this->render('index', [
            'postArticleSearchForm' => $postArticleSearchForm,
        ]);
    }

    /**
     * Страница публикации
     * @param PostArticle $postArticle
     * @see \frontend\routes\Post::parseRequest()
     * @return string
     */
    public function actionView(PostArticle $postArticle)
    {
        $url = \yii\helpers\Url::to(['posts/view', $postArticle]);
        if ($url != '/' . ltrim(\Yii::$app->request->pathInfo, '/')) {
            return $this->redirect($url, 301);
        }

        $postArticle->incrementViews();

        $isSimSimPost = $this->showHiddenPage();

        $this->view->params['languages-urls'] = array_map(function (PostArticle $_postArticle) use ($postArticle) {
            if (!$_postArticle->visible || !$_postArticle->statusIsActive()) {
                return ['url' => ['site/index', 'language' => Language::getSlugById($_postArticle->lang_id)]];
            }
            return [
                'url' => ['posts/view', $_postArticle],
                'active' => $_postArticle->id === $postArticle->id,
            ];
        }, $postArticle->getTranslationMap());

        return $this->render('view', [
            'postArticle' => $postArticle,
            'isSimSimPost' => $isSimSimPost,
        ]);
    }
}
