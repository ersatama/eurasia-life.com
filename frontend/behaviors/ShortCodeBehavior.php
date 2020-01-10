<?php

namespace frontend\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;
use common\models\ShortCode;
use frontend\widgets\ShortCode as ShortCodeWidget;

/**
 * Class ShortCodeBehavior – Поведение помогает контроллерам загружать шорткоды
 *
 * @property $owner Controller
 *
 * @package frontend\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class ShortCodeBehavior extends Behavior
{
    /**
     * @var array – Список экшенов контроллера
     */
    public $actions = [];

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            Controller::EVENT_BEFORE_ACTION => 'beforeAction',
        ];
    }

    /**
     * Запускаем до основного действия
     */
    public function beforeAction()
    {
        $actionId = $this->owner->action->id;

        if (!isset($this->actions[$actionId])) {
            return;
        }

        $this->loadShortCodesByFor($this->actions[$actionId]);
    }

    /**
     * Загружает шорткоды
     * @param string $for
     */
    public function loadShortCodesByFor($for)
    {
        $shortCodes = ShortCode::find()
            ->active()
            ->andWhere(['for' => $for])
            ->with('files')
            ->indexBy('short_code')->all();

        $currentShortCodes = isset(Yii::$app->view->blocks[ShortCodeWidget::VIEW_PARAMS_KEY])
            ? Yii::$app->view->blocks[ShortCodeWidget::VIEW_PARAMS_KEY]
            : [];

        $shortCodes = array_merge($currentShortCodes, $shortCodes);

        Yii::$app->view->blocks[ShortCodeWidget::VIEW_PARAMS_KEY] = $shortCodes;
    }

    /**
     * Вернет шорткод по имени [name]
     * @param $name
     * @return null|ShortCode
     */
    public function getShortCodeByName($name)
    {
        $shortCodes = isset(Yii::$app->view->blocks[ShortCodeWidget::VIEW_PARAMS_KEY])
            ? Yii::$app->view->blocks[ShortCodeWidget::VIEW_PARAMS_KEY] : [];

        return is_array($shortCodes) && isset($shortCodes[$name]) ? $shortCodes[$name] : null;
    }

    /**
     * Вернет контент шорткода по его имени [name]
     * @param $name
     * @return null|mixed
     */
    public function getShortCodeContentByName($name)
    {
        return ($sc = $this->getShortCodeByName($name)) ? $sc->content : null;
    }
}
