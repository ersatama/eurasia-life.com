<?php

namespace common\base;

/**
 * Class FileTarget — Переназначил, чтоб записывать простые сообщениями, без трассировок и т.д.
 *
 * @package common\base
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class FileTarget extends \yii\log\FileTarget
{
    /**
     * @var bool
     */
    public $inline = true;

    /**
     * @inheritdoc
     */
    public function formatMessage($message)
    {
        if ($this->inline && isset($message[0]) && $message[0] instanceof \Exception) {
            /**
             * @var $e \Exception
             */
            $e = $message[0];
            $message[0] = sprintf('%s in %s:%d', $e->getMessage(), $e->getFile(), $e->getLine());
        }

        return parent::formatMessage($message);
    }

    /**
     * @inheritdoc
     */
    public function getContextMessage()
    {
        return $this->inline ? '' : parent::getContextMessage();
    }
}