<?php

namespace frontend\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\ViewEvent;
use yii\helpers\Url;
use yii\web\View;

/**
 * Class ControllerHelper — Поведение контроллеров, помогает собрать общий код
 *
 * @property yii\web\Controller $owner
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ControllerHelper extends Behavior
{
    use ControllerHelperArticleTrait;
    use ControllerHelperLandingPageTrait;
    use ControllerHelperLanguageTrait;
    use ControllerHelperPageTrait;

    /**
     * Избавляемся от ?page=1
     * @param string $name
     * @return \yii\web\Response|null
     */
    public function redirectPageOne(string $name = 'page'): ?\yii\web\Response
    {
        return Yii::$app->request->get($name) == 1
            ? $this->owner->redirect(rtrim(Url::current([$name => null]), '?'), 301)
            : null;
    }

    /**
     * @var bool - загружаем только один раз
     */
    private static $init = false;

    /**
     * Перед показом вьюхи
     * @param ViewEvent $event
     */
    public function beforeViewRender(ViewEvent $event)
    {
        if (static::$init) {
            return;
        }

        $this->landingPageBeforeViewRender();

        $this->languageBeforeViewRender();

        $this->pageBeforeViewRender();

        static::$init = true;
    }

    /**
     * @inheritdoc
     */
    public function attach($owner)
    {
        parent::attach($owner);

        $this->getView()->on(View::EVENT_BEFORE_RENDER, [$this, 'beforeViewRender']);
    }

    /**
     * @inheritdoc
     */
    public function detach()
    {
        $this->getView()->off(View::EVENT_BEFORE_RENDER, [$this, 'beforeViewRender']);

        parent::detach();
    }

    /**
     * @return \yii\base\View|View
     */
    protected function getView()
    {
        return $this->owner->getView();
    }
}
