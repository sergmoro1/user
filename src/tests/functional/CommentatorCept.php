<?php

use sergmoro1\user\tests\_support\FunctionalTester;
use sergmoro1\user\tests\common\_pages\LoginPage;
use sergmoro1\user\tests\common\_views\CreateUserForm;

/* @var $scenario Codeception\Scenario */

$I = new FunctionalTester($scenario);
$I->wantTo('login');

$loginPage = LoginPage::openBy($I);

$I->amGoingTo('login with correct credentials');
$loginPage->login('Lettie', '123456');
$I->expectTo('see that user is logged');
$I->seeLink('Logout');

$I->amGoingTo('see is there Commentator\'s profile');
$form = CreateUserForm::openBy($I);
$I->click('Lettie');
$I->click('Profile');

$I->wantTo('ensure Commentator form works');

$I->amGoingTo('submit form with no data');
$form->fill('', '');
$I->expectTo('see validations errors');
$I->see('Name cannot be blank.', '.help-block');
$I->see('Email cannot be blank.', '.help-block');

$I->amGoingTo('submit form with data');
$form->fill('Lettie', 'Lettie$ya.ru');
$I->expectTo('see validations errors');
$I->see('Email is not a valid email address.', '.help-block');

$form->fill('Lettie', 'Lettie@ya.ru');

$I->expectTo('see success message');
$I->see('Lettie\'s profile was successfully updated.', '.alert');
