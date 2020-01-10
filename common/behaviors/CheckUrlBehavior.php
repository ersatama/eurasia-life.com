<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\web\Controller;

/**
 * Class CheckUrlBehavior – Поведение для Контроллеров
 * Помогает следить чтоб не было много URL'ов на один action
 *
 * @property Controller $owner
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class CheckUrlBehavior extends Behavior
{
    /**
     * '$action' => '$path',
     * 'index' => '/news'
     * @var array
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
     * @throws \yii\base\ExitException
     */
    public function beforeAction()
    {
        $actionId = $this->owner->action->id;

        if (isset($this->actions[$actionId])) {
            $this->checkUrl($this->actions[$actionId]);
        }
    }

    /**
     * Проверит переданный URL с текущим и если он не подходит, то сделает переадресацию
     * @param $url
     * @throws \yii\base\ExitException
     */
    public function checkUrl($url)
    {
        if ($url != '/' . Yii::$app->request->pathInfo || $_SERVER['REQUEST_URI'] == '/index.php') {
            $this->owner->redirect($url, 301);
            Yii::$app->end();
        }
    }
}
