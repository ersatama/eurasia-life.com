<?php

namespace common\models;

use common\base\ActiveQuery;
use common\behaviors\StatusQueryBehavior;

/**
 * Class UserQuery – Выборка пользователей
 *
 * @mixin StatusQueryBehavior
 *
 * @method User|array|null|\yii\db\ActiveRecord one($db = null)
 * @method User[]|array|\yii\db\ActiveRecord[] all($db = null)
 *
 * @package common\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class UserQuery extends ActiveQuery
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            StatusQueryBehavior::class,
        ];
    }

    /**
     * Добавит условие email = $email
     * @param $email
     * @return UserQuery
     */
    public function email($email)
    {
        return $this->andWhere(['email' => $email]);
    }

    /**
     * Вернет одного активного пользователя по эл. почте
     * @param string $email
     * @return User|array|null|\yii\db\ActiveRecord
     */
    public function oneActiveByEmail($email)
    {
        return $this->email($email)->active()->limit(1)->one();
    }
}

