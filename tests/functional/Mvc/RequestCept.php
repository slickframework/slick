<?php

/** @var  \Codeception\Scenario $scenario */
$I = new TestGuy($scenario);

$I->wantTo('get default page');
$I->amOnPage('/');
$I->see('Bootstrap');
