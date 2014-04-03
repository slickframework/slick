<?php

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);

$I->wantTo('get default page');
$I->amOnPage('/');
$I->see('Home page');
$I->see('app.css');
$I->see('jquery.min.js');
