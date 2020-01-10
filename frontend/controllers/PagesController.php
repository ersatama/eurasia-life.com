<?php

namespace frontend\controllers;

use yii\web\Controller;
use common\models\Page;
use frontend\behaviors\ControllerHelper;
use frontend\behaviors\RequestForm;

/**
 * Class PagesController – Контент-страницы на сайте
 *
 * @mixin ControllerHelper
 * @mixin RequestForm
 *
 * @package frontend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            RequestForm::class,
        ];
    }

    /**
     * Показываем контент-страницу
     * @see \frontend\routes\Page::parseRequest()
     *
     * @param Page $page
     * @return string
     */
    public function actionView(Page $page)
    {
        $page->incrementViews();

        return $this->render('view', [
            'page' => $page,
        ]);
    }
}
