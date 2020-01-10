<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;
use common\behaviors\CreatedUpdatedBehavior;
use common\traits\CommonAttrsTrait;
use common\traits\SaveWithExceptionTrait;

/**
 * Class User – Пользователь
 *
 * @property string $email
 * @property string $password_hash
 * @property string $name
 * @property integer $identity_at
 * @property integer $login_at
 *
 * @mixin UserStatusBehavior
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class User extends ActiveRecord implements IdentityInterface
{
    use CommonAttrsTrait,
        UserIdentityInterfaceTrait,
        SaveWithExceptionTrait;

    /**
     * @inheritdoc
     * @return UserQuery
     */
    public static function find()
    {
        return new UserQuery(get_called_class());
    }

    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            UserStatusBehavior::class,
            CreatedUpdatedBehavior::class,
        ];
    }

    /**
     * Проверка пароля
     * @param string $password
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Установит хэш-пароля в соотв. атрибут
     * @param string $password
     * @return $this
     * @throws \yii\base\Exception
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
        return $this;
    }

    /**
     * Обновит время входа пользователя
     */
    public function updateLoginAt()
    {
        $this->login_at = time();
        $this->save(true, ['login_at']);
    }

    /**
     * Обновит время идентификации пользователя
     */
    public function updateIdentityAt()
    {
        $this->identity_at = time();
        $this->save(true, ['identity_at']);
    }
}
