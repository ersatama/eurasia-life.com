<?php

namespace common\behaviors;

use Yii;
use yii\base\Behavior;
use yii\base\Model;

/**
 * Class NotifyBehavior – Поведение дает возможность отправлять пользователям alert-сообщения
 * - Можно подключать к контроллерам и моделям форм
 * - Если подключили к модели формы, то позиция (namespace) будет с перфиксом названия формы [formName()]
 * - Показывать можно через виджет \common\widgets\Notify
 *
 * @package common\behaviors
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class NotifyBehavior extends Behavior
{
    const POSITION_DEFAULT = 'default';

    const TYPE_SUCCESS = 'success';
    const TYPE_DANGER = 'danger';
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';

    /**
     * Записывает сообщение
     * @param $type
     * @param $message
     * @param null $position
     * @return \yii\web\Controller|Model|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function notify($type, $message, $position = null)
    {
        if ($this->owner instanceof Model) {
            $position = $this->owner->formName() . $position;
        }

        if ($position === null) {
            $position = static::POSITION_DEFAULT;
        }

        $session = Yii::$app->getSession();

        $messages = $session->getFlash($position, []);

        $messages[] = [
            'type' => $type,
            'message' => $message,
        ];

        $session->setFlash($position, $messages);

        return $this->owner;
    }

    /**
     *
     * Helpers
     *
     */

    /**
     * Success
     * @param $message
     * @param null|string $position
     * @return \yii\web\Controller|Model|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function notifySuccess($message, $position = null)
    {
        return $this->notify(static::TYPE_SUCCESS, $message, $position);
    }

    /**
     * Danger
     * @param $message
     * @param null|string $position
     * @return \yii\web\Controller|Model|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function notifyDanger($message, $position = null)
    {
        return $this->notify(static::TYPE_DANGER, $message, $position);
    }

    /**
     * Info
     * @param $message
     * @param null|string $position
     * @return \yii\web\Controller|Model|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function notifyInfo($message, $position = null)
    {
        return $this->notify(static::TYPE_INFO, $message, $position);
    }

    /**
     * Warning
     * @param $message
     * @param null|string $position
     * @return \yii\web\Controller|Model|\yii\base\Component
     * @throws \yii\base\InvalidConfigException
     */
    public function notifyWarning($message, $position = null)
    {
        return $this->notify(static::TYPE_WARNING, $message, $position);
    }
}
