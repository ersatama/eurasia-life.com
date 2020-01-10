<?php

namespace frontend\behaviors;

use Yii;
use yii\web\Application;
use yii\base\Behavior;
use common\models\Language as LanguageModel;

/**
 * Class Language — Поведение помогает распознать язык тек. страницы
 *
 * @property yii\web\Application $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Language extends Behavior
{
    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Application::EVENT_BEFORE_REQUEST => 'beforeRequest',
        ];
    }

    /**
     * @throws \yii\base\ExitException
     */
    public function beforeRequest()
    {
        $pathInfo = Yii::$app->request->pathInfo;

        $slugs = LanguageModel::getAllSlugs();
        if (!$slugs) {
            return;
        }

        if (!preg_match('/^(' . implode('|', $slugs) . ')((?:\/|$))/uis', $pathInfo, $matches)) {
            return;
        }

        $lang = $matches[1];

        if ((!isset($matches[2]) || !$matches[2]) && Yii::$app->language === $lang) {
            Yii::$app->response->redirect("/", 301);
            Yii::$app->end();
            return;
        }

        Yii::$app->language = $lang;
    }
}
