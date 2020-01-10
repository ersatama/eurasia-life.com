<?php

namespace console\controllers;

use yii\base\DynamicModel;
use yii\console\Controller;
use yii\console\ExitCode;

use common\models\User;
use common\models\UserQuery;

/**
 * Class UserController – Управляем пользователями с консоли
 *
 * @package console\controllers
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class UserController extends Controller
{
    /**
     * Creates a new user
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $email = $this->promptEmail();
        $password = $this->promptPassword();
        $name = $this->promptName();

        $user = new User();
        $user->email = $email;
        $user->name = $name;
        $user->setPassword($password);
        $user->changeStatusToActive();
        $user->setCreatedAttributes();
        $user->saveWithException();

        echo "New user created successfully.\n";

        return ExitCode::OK;
    }

    /**
     * @return string
     */
    protected function promptEmail()
    {
        return $this->prompt('Enter e-mail:', [
            'required' => true,
            'validator' => function ($input, &$error) {
                $model = DynamicModel::validateData(['email' => $input], [
                    ['email', 'trim'],
                    ['email', 'required'],
                    ['email', 'email'],
                    ['email', 'string', 'max' => 255],
                    ['email', 'unique', 'targetClass' => User::class, 'filter' => function (UserQuery $query) {
                        $query->active()->limit(1);
                    }],
                ]);

                return ($error = $model->getFirstError('email')) == '';
            },
            'error' => 'укажите Email',
        ]);
    }

    /**
     * @return string
     */
    protected function promptPassword()
    {
        return $this->prompt('Enter password:', [
            'required' => true,
            'validator' => function ($input, &$error) {
                $model = DynamicModel::validateData(['password' => $input], [
                    ['password', 'required'],
                    ['password', 'string', 'min' => 6],
                ]);

                return ($error = $model->getFirstError('password')) == '';
            },
            'error' => 'укажите Password'
        ]);
    }

    /**
     * @return string
     */
    protected function promptName()
    {
        return $this->prompt('Enter name:', [
            'required' => true,
            'validator' => function ($input, &$error) {
                $model = DynamicModel::validateData(['name' => $input], [
                    ['name', 'required'],
                    ['name', 'string', 'max' => 255],
                ]);

                return ($error = $model->getFirstError('name')) == '';
            },
            'error' => 'укажите Name'
        ]);
    }
}
