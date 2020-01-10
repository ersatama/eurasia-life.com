<?php

namespace common\models;

use common\behaviors\StatusBehavior;

/**
 * Class UserStatusBehavior – Поведение модели пользователей
 * - добавляем статусы пользователям Регистрация и Заблокирован
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class UserStatusBehavior extends StatusBehavior
{
    const STATUS_SIGN_UP = 101; // регистрация

    const STATUS_BLOCKED = 102; // заблокирован

    /**
     * @inheritdoc
     */
    public static function getStatusLabels()
    {
        $return = parent::getStatusLabels();
        $return[self::STATUS_SIGN_UP] = 'регистрация';
        $return[self::STATUS_BLOCKED] = 'заблокирован';
        return $return;
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * Тек. статус = Регистрация
     * @return bool
     */
    public function statusIsSignup()
    {
        return $this->statusIs(static::STATUS_SIGN_UP);
    }

    /**
     * Тек. статус = Заблокирован
     * @return bool
     */
    public function statusIsBlocked()
    {
        return $this->statusIs(static::STATUS_BLOCKED);
    }

    /**
     * Сменить статус на Регистрация
     * @return \yii\base\Component
     */
    public function changeStatusToSignUp()
    {
        return $this->changeStatusTo(static::STATUS_SIGN_UP);
    }

    /**
     * Сменить статус на Заблокирован
     * @return \yii\base\Component
     */
    public function changeStatusToBlocked()
    {
        return $this->changeStatusTo(static::STATUS_BLOCKED);
    }
}
