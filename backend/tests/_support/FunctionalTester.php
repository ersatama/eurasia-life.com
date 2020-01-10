<?php
namespace backend\tests;

/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method \Codeception\Lib\Friend haveFriend($name, $actorClass = NULL)
 *
 * @SuppressWarnings(PHPMD)
 */
class FunctionalTester extends \Codeception\Actor
{
    use _generated\FunctionalTesterActions;

    /**
     * Define custom actions here
     */

    /**
     * Я вижу такую ошибку ($message) валидатора
     *
     * @param $message
     */
    public function seeValidationError($message)
    {
        $this->see($message, '.help-block');
    }

    /**
     * Я не вижу такую ошибку ($message) валидатора
     *
     * @param $message
     */
    public function dontSeeValidationError($message)
    {
        $this->dontSee($message, '.help-block');
    }
}
