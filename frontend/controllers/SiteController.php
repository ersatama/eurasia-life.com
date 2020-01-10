<?php

namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use common\models\PostArticle;
use common\models\Language;
use frontend\actions\ErrorAction;
use frontend\actions\ImageResize;
use frontend\actions\Rss;
use frontend\behaviors\ControllerHelper;
use frontend\behaviors\RequestForm;
use frontend\behaviors\ShortCodeBehavior;

/**
 * Class SiteController — Основной контроллер
 *
 * @mixin ControllerHelper
 * @mixin RequestForm
 * @mixin ShortCodeBehavior
 *
 * @package frontend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            RequestForm::class,
            [
                'class' => ShortCodeBehavior::class,
                'actions' => [
                    'index' => 'page-main--' . Language::getIdBySlug(Yii::$app->language),
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => ErrorAction::class,
            'resized' => ImageResize::class,
            'rss' => Rss::class,
        ];
    }

    /**
     * Страница: Главная
     * @param null|string $language
     * @return string|Response|null
     */
    public function actionIndex($language = null)
    {
        // проверим УРЛ
        if (($r = $this->checkUrlForActionIndex())) {
            return $r;
        }

        // Новости
        $postArticles = $this->findNewsForActionIndex();

        return $this->render('index', [
            'postArticles' => $postArticles,
        ]);
    }

    /**
     * @return PostArticle[]
     */
    protected function findNewsForActionIndex()
    {
        $query = PostArticle::find();
        $query->active();
        $query->visible();
        $query->published();
        $query->andWhere(['lang_id' => Language::getIdBySlug(Yii::$app->language)]);
        $query->sortByPosition();
        $query->limit(3);
        $query->with('mainImage');
        $postArticles = $query->all();
        return $postArticles;
    }

    /**
     * @return Response|null
     */
    protected function checkUrlForActionIndex(): ?Response
    {
        $request = Yii::$app->request;

        // remove index.php
        $uri = $request->url;
        if (in_array(parse_url($uri, PHP_URL_PATH), ['/index.php', '/site', '/site/index']) || $uri == '/?') {
            $newUrl = '/';
            ($_t = parse_url($uri, PHP_URL_QUERY)) && ($newUrl .= '?' . $_t);
            return $this->redirect($newUrl, 301);
        }

        return null;
    }

    /**
     * @todo???
     * Тестим 500 ошибку
     * @throws \yii\db\Exception
     */
    public function actionUps()
    {
        throw new \yii\db\Exception('test');
    }
}
