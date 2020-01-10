<?php

namespace backend\tests\unit\models;

use Yii;
use backend\models\LoginForm;
use backend\fixtures\UserFixture as UserFixture;

/**
 * Class LoginFormTest – юнит тесты формы авторизации
 *
 * @package backend\tests\unit\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LoginFormTest extends \Codeception\Test\Unit
{
    /**
     * @var \backend\tests\UnitTester
     */
    protected $tester;

    public function _before()
    {
        $this->tester->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'user.php'
            ]
        ]);
    }

    public function testLoginNoUser()
    {
        $model = new LoginForm([
            'email' => 'not_existing_email@grafica.kz',
            'password' => 'not_existing_password',
        ]);

        expect('model should not login user', $model->login())->false();
        expect('user should not be logged in', Yii::$app->user->isGuest)->true();
    }

    public function testLoginWrongPassword()
    {
        $model = new LoginForm([
            'email' => 'arman@grafica.kz',
            'password' => 'wrong_password',
        ]);

        expect('model should not login user', $model->login())->false();
        expect('error message should be set', $model->errors)->hasKey('password');
        expect('user should not be logged in', Yii::$app->user->isGuest)->true();
    }

    public function testLoginCorrect()
    {
        $model = new LoginForm([
            'email' => 'arman@grafica.kz',
            'password' => 'password_0',
        ]);

        expect('model should login user', $model->login())->true();
        expect('error message should not be set', $model->errors)->hasntKey('password');
        expect('user should be logged in', Yii::$app->user->isGuest)->false();
    }
}
