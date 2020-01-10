<?php

namespace backend\tests\functional;

use \backend\tests\FunctionalTester;
use common\fixtures\UserFixture as UserFixture;

/**
 * Class LoginCest – функц. тест формы авторизации
 *
 * @package backend\tests\functional
 * @author Arman Kazgozhin <arman.kazgozhin@gmail.com>
 */
class LoginCest
{
    public function _before(FunctionalTester $I)
    {
        $I->haveFixtures([
            'user' => [
                'class' => UserFixture::class,
                'dataFile' => codecept_data_dir() . 'login_data.php'
            ]
        ]);
    }

    /**
     * @param FunctionalTester $I
     */
    public function loginUser(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->fillField("//input[@type='email'][@name='LoginForm[email]']", 'arman@grafica.kz');
        $I->fillField(['name' => 'LoginForm[password]'], 'password_0');
        $I->click('form button[type=submit]');

        $I->see('Выход', 'form button[type=submit]');
        $I->dontSeeLink('Вход');
    }

    protected function formParams($email, $password)
    {
        return [
            'LoginForm[email]' => $email,
            'LoginForm[password]' => $password,
        ];
    }

    public function checkEmpty(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->submitForm('#site-login_form', $this->formParams('', ''));
        $I->seeValidationError('Необходимо заполнить «Эл. почта».');
        $I->seeValidationError('Необходимо заполнить «Пароль».');
    }

    public function checkWrongPassword(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->submitForm('#site-login_form', $this->formParams('not_existing_email@grafica.kz', 'not_existing_password'));
        $I->seeValidationError('Эл. почта или пароль указаны неверно.');
    }

    public function checkValidLogin(FunctionalTester $I)
    {
        $I->amOnPage('/site/login');
        $I->submitForm('#site-login_form', $this->formParams('arman@grafica.kz', 'password_0'));
        $I->see('Выход', 'form button[type=submit]');
        $I->dontSeeLink('Вход');
    }
}
