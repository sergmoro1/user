<?php

use sergmoro1\user\tests\_support\FunctionalTester;
use sergmoro1\user\tests\common\_pages\SignupPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure signup page works');

$signupPage = SignupPage::openBy($I);

$I->amGoingTo('submit signup form with no data');
$signupPage->signup('', '', '');
$I->expectTo('see validations errors');
$I->see('Name cannot be blank.', '.help-block');
$I->see('Email cannot be blank.', '.help-block');
$I->see('Password cannot be blank.', '.help-block');

$I->amGoingTo('try to signup with wrong credentials');
$signupPage->signup('Admin', 'admin', 'wrong');
$I->expectTo('see validations errors');
$I->see('Email is not a valid email address.', '.help-block');
$I->see('Password should contain at least 6 characters.', '.help-block');

$I->amGoingTo('try to signup');
$signupPage->signup('Admin', 'admin@ya.ru', '123456');
$I->expectTo('see confirmation message');
$I->see('Admin, thank you for registering', '.alert-success');
$I->see('check email, to complete the procedure.', '.alert-success');
