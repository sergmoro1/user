<?php

namespace sergmoro1\user\tests\common\_pages;

use yii\codeception\BasePage;

/**
 * Represents Password Reset Request page
 * @property FunctionalTester $actor
 */
class PasswordResetRequestPage extends BasePage
{
    public $route = 'user/site/request-password-reset';

    /**
     * @param string $email
     */
    public function reset($email)
    {
        $this->actor->fillField('input[name="PasswordResetRequestForm[email]"]', $email);
        $this->actor->click('submit-button');
    }
}
