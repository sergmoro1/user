<?php

namespace sergmoro1\user\tests\common\_pages;

use yii\codeception\BasePage;

/**
 * Represents loging page
 * @property FunctionalTester $actor
 */
class LoginPage extends BasePage
{
    public $route = '/user/site/login';

    /**
     * @param string $username
     * @param string $password
     */
    public function login($username, $password)
    {
        $this->actor->fillField('input[name="LoginForm[username]"]', $username);
        $this->actor->fillField('input[name="LoginForm[password]"]', $password);
        $this->actor->click('login-button');
    }
}
