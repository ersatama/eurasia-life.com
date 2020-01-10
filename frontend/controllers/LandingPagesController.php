<?php

namespace frontend\controllers;

use yii\web\Controller;
use yii\web\Response;
use common\models\LandingPage;
use common\models\Language;
use frontend\behaviors\ControllerHelper;
use frontend\behaviors\RequestForm;
use frontend\models\RequestForm as RequestFormModel;
use frontend\behaviors\ShortCodeBehavior;

/**
 * Class LandingPagesController – Управляет посадочными страницами на сайте
 *
 * @mixin ControllerHelper
 * @mixin RequestForm
 * @mixin ShortCodeBehavior
 *
 * @package frontend\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LandingPagesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            ControllerHelper::class,
            RequestForm::class,
            ShortCodeBehavior::class,
        ];
    }

    /**
     * Показываем посадочную страницу
     * @see \frontend\routes\LandingPage::parseRequest()
     *
     * @param LandingPage $landingPage
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function actionView(LandingPage $landingPage)
    {
        $requestForm = $this->getRequestForm(RequestFormModel::POSITION_LANDING_PAGE);
        if ($requestForm instanceof Response) {
            return $requestForm;
        }

        $languagesUrls = [];
        foreach ($landingPage->getTranslationMap() as $langId => $_landingPage) {
            $languagesUrls[$langId] = [
                'url' => $_landingPage ? ['landing-pages/view', $_landingPage] : ['site/index', 'language' => Language::getSlugById($langId)],
                'active' => $_landingPage ? $_landingPage->id === $landingPage->id : false,
            ];
        }

        $this->view->params['languages-urls'] = $languagesUrls;

        $this->loadShortCodesByFor('landing-page-' . $landingPage->id);

        return $this->render('view', [
            'landingPage' => $landingPage,
            'requestForm' => $requestForm,
        ]);
    }
}
