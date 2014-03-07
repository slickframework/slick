<?php

/**
 * Scaffolding edit page test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);
$I->wantTo('use scaffold to edit a record');
$I->am('developer');

$I->amOnPage('/posts/edit/1');
$I->see('My blog post');

$I->fillField('#title-input', '');
$I->click('Save');

$I->see('Value cannot be empty.');
$I->see('has-error');

$I->fillField('#title-input', 'My blog post edited');
$I->click('Save');

$I->seeInCurrentUrl('posts/index');
$I->see('My blog post edited');

$I->amOnPage('/posts/edit/1');
$I->fillField('#title-input', 'My blog post');
$I->click('Save');
$I->seeInCurrentUrl('posts/index');
$I->see('My blog post');