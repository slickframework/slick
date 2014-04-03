<?php
/**
 * Scaffolding show page test case
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.0.0
 */

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);
$I->wantTo('use scaffold to view a record');
$I->am('developer');

$I->amOnPage('/posts/index');
$I->seeLink('View post', 'posts/show/1');

$I->amOnPage('/posts/show/1');
$I->see('My blog post');
$I->see('Edit post', 'a');
$I->see('Delete post', 'a');

$I->amOnPage('/posts/show/100');
$I->seeInCurrentUrl('index');
