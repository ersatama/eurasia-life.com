<?php

namespace backend\tests\unit\models;

use common\models\User;
use backend\models\PasswordForm;
use backend\fixtures\UserFixture as UserFixture;

/**
 * Class PasswordFormTest – юнит тесты формы смены пароля
 *
 * @package backend\tests\unit\models
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class PasswordFormTest extends \Codeception\Test\Unit
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

    public function testChangePasswordNoData()
    {
        $user = User::find()->oneActiveByEmail('arman@grafica.kz');

        $model = new PasswordForm(($user ? $user->id : 0), [
            'password' => '',
            'new_password' => '',
        ]);

        expect('model should not change password', $model->changePassword())->false();

        expect('error message should be set', $model->errors)->hasKey('password');

        expect('error message should be set', $model->errors)->hasKey('new_password');
    }

    public function testChangeWrongPassword()
    {
        $user = User::find()->oneActiveByEmail('arman@grafica.kz');

        $model = new PasswordForm(($user ? $user->id : 0), [
            'password' => 'wrong password',
            'new_password' => 'new password',
        ]);

        expect('model should not change password', $model->changePassword())->false();

        expect('error message should be set', $model->errors)->hasKey('password');
    }

    public function testChangeCorrectPassword()
    {
        $user = User::find()->oneActiveByEmail('arman@grafica.kz');

        $model = new PasswordForm(($user ? $user->id : 0), [
            'password' => 'password_0',
            'new_password' => 'new password',
        ]);

        expect('model should change password', $model->changePassword())->true();

        expect('error message should be set', $model->errors)->hasntKey('password');
    }
}
