<?php

/**
 * Scaffolding delete page test case
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

$I->amOnPage('/posts/delete/1');
$I->seeInCurrentUrl('index');

$I->amOnPage('/posts/delete/2');
$I->seeInCurrentUrl('index');

$I->amOnPage('/posts/show/1');
$I->submitForm('.modal-content form', ['id' => 200]);

$I->amOnPage('/posts/add');
$I->fillField('#title-input', 'a simple test');
$I->click('Save');
$I->seeInDatabase('posts', ['title' => 'a simple test']);
$I->submitForm('.modal-content form', ['id' => 2]);
$I->dontSeeInDatabase('posts', ['id' => 2]);
