<?php

namespace backend\models;

use yii\base\Model;
use common\models\User;

/**
 * Class PasswordForm – Форма смены пароля
 * @package backend\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PasswordForm extends Model
{
    /*/ - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - /*/

    /**
     * @var string - текущий пароль
     */
    public $password;

    /**
     * @var string - новый пароль
     */
    public $new_password;

    /**
     * @var int - id-юзера
     */
    protected $user_id;

    /**
     * @var User - юзер которому меняем пароль
     */
    protected $user;

    /**
     * @inheritdoc
     * @param int $userId - id-юзера которому меняем пароль
     * @param array $config
     */
    public function __construct($userId, array $config = [])
    {
        parent::__construct($config);
        $this->user_id = $userId;
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['password', 'string'],
            ['password', 'required'],
            ['password', 'validatePassword'],
            ['new_password', 'required'],
            ['new_password', 'string', 'min' => 6],
        ];
    }

    /**
     * Проверка пароля
     * @param $attribute
     */
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Текущий пароль неверен');
            }
        }
    }

    /**
     * Смена пароля - Главный метод
     * @return bool
     * @throws \yii\base\Exception
     */
    public function changePassword()
    {
        return $this->validate() && $this->getUser()->setPassword($this->new_password)->save(false, ['password_hash']);
    }

    /**
     * Вернет пользователя
     * @return User
     */
    public function getUser()
    {
        if (!($this->user instanceof User)) {
            $this->user = User::find()->oneActiveById($this->user_id);
        }

        return $this->user;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'password' => 'Текущий пароль',
            'new_password' => 'Новый пароль',
        ];
    }
}
