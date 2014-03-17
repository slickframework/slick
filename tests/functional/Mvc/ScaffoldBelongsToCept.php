<?php

/**
 * Scaffolding add belongs to entity test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);
$I->wantTo('perform actions and see result');

$I->am("developer");
$I->amOnPage('/comments/add');
$I->see('Post');
$I->fillField('#post-input', 1);
$I->fillField('#body-input', 'A simple comment');
$I->click('Save');
$I->seeInCurrentUrl('show');
$I->see('A simple comment');