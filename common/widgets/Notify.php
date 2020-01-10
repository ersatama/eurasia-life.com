<?php

namespace common\widgets;

use Yii;
use yii\base\Widget;
use yii\bootstrap\Alert as YiiBootstrapAlert;
use common\behaviors\NotifyBehavior;

/**
 * Class Notify – Виджет помогает показывать alert-сообщения
 * - указанные через \common\behaviors\NotifyBehavior
 *
 * @package common\widgets
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class Notify extends Widget
{
    /**
     * @var string - позиция (namespace)
     */
    public $position;

    /**
     * @inheritdoc
     * @throws \Exception
     */
    public function run()
    {
        $position = $this->position;

        if ($position === null) {
            $position = NotifyBehavior::POSITION_DEFAULT;
        }

        $messages = Yii::$app->session->getFlash($position);

        if (!$messages || !is_array($messages)) {
            return '';
        }

        $return = '';

        foreach ($messages as $message) {
            $return .= YiiBootstrapAlert::widget([
                'options' => [
                    'class' => 'alert-' . $message['type'],
                ],
                'body' => $message['message'],
                'closeButton' => false
            ]);
        }

        return $return;
    }
}
