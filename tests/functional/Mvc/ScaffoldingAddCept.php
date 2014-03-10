<?php

/**
 * Scaffolding add page test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);
$I->wantTo('use scaffold to add a record');
$I->am('developer');

$I->amOnPage('/posts/add');
$I->seeElement('#add-post');
$I->seeElement('#title-input-group');
$I->seeElement('#title-input');
$I->seeElement('#published-input');

$I->fillField('#title-input', 'Functional test post');
$I->fillField('#body-input', 'This is a functional test post');
$I->click('Save');

$I->seeInDatabase('posts', array('title' => 'Functional test post'));
$I->seeInCurrentUrl('posts/show');
$I->see('Functional test post');

/** Trying validation */
$I->amOnPage('/posts/add');
$I->fillField('#body-input', 'Body <b>with estrange code </b>test');
$I->click('Save');

$I->seeInCurrentUrl('posts/add');
$I->see('Value cannot be empty.', 'p');
$I->see('Body with estrange code test');
