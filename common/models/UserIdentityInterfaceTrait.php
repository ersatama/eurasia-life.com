<?php

namespace common\models;

use yii\base\NotSupportedException;

/**
 * Trait UserIdentityInterfaceTrait — трейт для модели пользователей
 * - реализуем интерфейс \yii\web\IdentityInterface
 *
 * @mixin User
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
trait UserIdentityInterfaceTrait
{
    /**
     * @inheritdoc
     * @return User|null
     */
    public static function findIdentity($id)
    {
        $user = static::find()->oneActiveById($id);
        if ($user instanceof static) {
            $user->updateIdentityAt();
        }

        return $user;
    }

    /**
     * @inheritdoc
     * @throws NotSupportedException
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     * @throws NotSupportedException
     */
    public function getAuthKey()
    {
        throw new NotSupportedException('"getAuthKey" is not implemented.');
    }

    /**
     * @inheritdoc
     * @throws NotSupportedException
     */
    public function validateAuthKey($authKey)
    {
        throw new NotSupportedException('"validateAuthKey" is not implemented.');
    }
}
