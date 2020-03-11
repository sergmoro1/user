<?php

namespace sergmoro1\user\tests\common\_views;

use yii\codeception\BasePage;

/**
 * Represents User form
 * @property FunctionalTester $actor
 */
class CreateUserForm extends BasePage
{
    public $route = '/user/user/update';

    public function init()
    {
        $this->route = Url::to(['/user/user/update', 'id' => 2]);
    }
    
    /**
     * @param string $username
     * @param string $email
     */
    public function fill($username, $email)
    {
        $this->actor->fillField('input[name="User[username]"]', $username);
        $this->actor->fillField('input[name="User[email]"]', $email);
        $this->actor->click('Save', '.btn-success');
    }
}
