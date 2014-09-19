<?php
$I = new TestGuy($scenario);
$I->wantTo('perform actions and see result');

$I->amOnPage('/pages/home');
$I->see('Home page');
