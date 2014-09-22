<?php

/**
 * MVC application
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 * @copyright 2014 Filipe Silva
 * @license   http://www.opensource.org/licenses/mit-license.php MIT License
 * @since     Version 1.1.0
 */

namespace Mvc;

use Slick\Mvc\Exception\ActionNotFoundException;
use Slick\Mvc\Exception\ControllerNotFoundException;
use Slick\Mvc\Exception\RenderingErrorException;
use TestGuy;

/**
 * MVC application
 *
 * @package   Test\Mvc
 * @author    Filipe Silva <silvam.filipe@gmail.com>
 */
class ApplicationCest
{

    public function loadBasicRequest(TestGuy $I) {
        $I->amOnPage('pages/home');
        $I->see('Home page');
        $I->see('Slick framework tests', 'title');
    }

    public function loadRedirectRequest(TestGuy $I)
    {
        $I->amOnPage('/');
        $I->see('Home page');
        $I->see('Slick framework tests', 'title');
    }

    /**
     * @param TestGuy $I
     * @expectedException \Slick\Mvc\Exception\ControllerNotFoundException
     */
    public function loadAnUnknownController(TestGuy $I)
    {
        try {
            $I->amOnPage('/unknown');
        } catch (ControllerNotFoundException $exp) {
            $I->expectTo("Get a ControllerNotFoundException exception");
        }

        try {
            $I->amOnPage('/pages/unknown');
        } catch (ActionNotFoundException $exp) {
            $I->expectTo("Get a ActionNotFoundException exception");
        }

        try {
            $I->amOnPage('/pages/errorPage');
        } catch (RenderingErrorException $exp) {
            $I->expectTo("Get a RenderingErrorException exception");
        }
    }

    public function loadDifferentLayoutAndView(TestGuy $I)
    {
        $I->amOnPage('pages/changeLayout');
        $I->see('Home page');
        $I->see('Other layout', 'title');
    }

    public function runningOtherMethods(TestGuy $I)
    {
        $I->amOnPage('/pages/multiple');
        $I->see('run', 'p');
        $I->see('after-run', 'p');
        $I->see('once=1', 'p');
    }

    public function passingQueryParams(TestGuy $I)
    {
        $I->amOnPage('/pages/home.html?test=1');
        $I->see('Home page');
        $I->dontSee('this is a test');
        $I->fillField('name', 'this is a test');
        $I->click('Submit');
        $I->see('this is a test');
    }

}