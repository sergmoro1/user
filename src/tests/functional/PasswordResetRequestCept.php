<?php

use sergmoro1\user\tests\_support\FunctionalTester;
use sergmoro1\user\tests\common\_pages\PasswordResetRequestPage;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('ensure request password reset page works');

$resetPage = PasswordResetRequestPage::openBy($I);

$I->amGoingTo('submit reset password form with no data');
$resetPage->reset('');
$I->expectTo('see validations errors');
$I->see('Email cannot be blank.', '.help-block');

$I->amGoingTo('try to reset password with wrong credentials');
$resetPage->reset('admin');
$I->expectTo('see validations errors');
$I->see('Email is not a valid email address.', '.help-block');

$I->amGoingTo('try to reset password with not yet reqistered email');
$resetPage->reset('admin@ya.ru');
$I->expectTo('see not yet registered message');
$I->see('There is no user with such email.', '.help-block');
