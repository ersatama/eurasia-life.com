<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\User;

/**
 * Class LoginForm – Форма авторизации
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LoginForm extends Model
{
    /**
     * @var string - эл. почта пользователя (логин)
     */
    public $email;

    /**
     * @var string - пароль пользователя (в открытом виде!)
     */
    public $password;

    /**
     * @var User - пользователь хочет авторизоваться
     */
    private $user;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],

            // password
            ['password', 'string'],
            ['password', 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email' => 'Эл. почта',
            'password' => 'Пароль',
        ];
    }

    /**
     * Валидация пароля.
     * @param string $attribute атрибут пароля
     * @param array $params доп. опции
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Эл. почта или пароль указаны неверно.');
            }
        }
    }

    /**
     * Авториация пользователя по эл. почте и паролю
     * @return bool
     */
    public function login()
    {
        return $this->validate() && Yii::$app->user->login($this->getUser());
    }

    /**
     * Вернет пользователя по эл. адресу
     * @return User|null
     */
    protected function getUser()
    {
        if (!($this->user instanceof User)) {
            $this->user = User::find()->oneActiveByEmail($this->email);
        }
        return $this->user;
    }
}
