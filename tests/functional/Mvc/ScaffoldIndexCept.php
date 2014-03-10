<?php

/**
 * Scaffolding index page test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);
$I->wantTo('use controller scaffolding');
$I->lookForwardTo('can accelerate model development');

$I->amOnPage('/posts/index.html?page=0');
$I->see('My blog post');
$I->seeLink('Add');
$I->see('Posts');
$I->seeResponseCodeIs(200);
$I->seeLink('Edit');


