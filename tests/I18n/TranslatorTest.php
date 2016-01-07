<?php

/**
 * This file is part of slick/i18n package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Slick\Tests\I18n;

use PHPUnit_Framework_TestCase as TestCase;
use Slick\I18n\TranslateMethods;
use Slick\I18n\Translator;
use Zend\I18n\Translator\Translator as ZendTranslator;
use PHPUnit_Framework_MockObject_MockObject as MockObject;

/**
 * Translator Test Case
 *
 * @package Slick\Tests\I18n
 * @author  Filipe Silva <silvam.filipe@gmail.com>
 */
class TranslatorTest extends TestCase
{

    /**
     * @var Translator
     */
    protected $translator;

    /**
     * Use the trait for translation
     */
    use TranslateMethods;

    /**
     * Creates the SUT object translator
     */
    protected function setUp()
    {
        parent::setUp();
        $this->translator = Translator::getInstance();
    }

    /**
     * Should create a translation service
     * @test
     */
    public function createTranslatorService()
    {
        $service = $this->translator->getTranslatorService();
        $this->assertInstanceOf(
            ZendTranslator::class,
            $service
        );
    }

    /**
     * Translate messages
     * @test
     */
    public function translateMessages()
    {
        $service = $this->getTranslatorServiceMock(
            ['translate', 'addTranslationFilePattern']
        );
        $service->expects($this->once())
            ->method('translate')
            ->with('user', 'default', 'en_US')
            ->willReturn('utilizador');

        $service->expects($this->once())
            ->method('addTranslationFilePattern')
            ->with('phparray', './I18n', '%s/default.php', 'default');

        $this->translator->translatorService = $service;
        $this->assertEquals('utilizador', $this->translate('user'));
    }

    /**
     * Should use translate plural from Zend service
     * @test
     */
    public function translateMessagesPlural()
    {
        $service = $this->getTranslatorServiceMock(['translatePlural']);
        $service->expects($this->once())
            ->method('translatePlural')
            ->with('user','users', 2, 'default', 'en_US')
            ->willReturn('utilizadores');
        $service->expects($this->never())
            ->method('addTranslationFilePattern');
        $this->translator->translatorService = $service;
        $this->assertEquals(
            'utilizadores',
            $this->translatePlural('user','users', 2)
        );
    }

    /**
     * Creates a Zend translator mocked object
     *
     * @param array $methods
     *
     * @return MockObject|ZendTranslator
     */
    protected function getTranslatorServiceMock($methods = [])
    {
        /** @var ZendTranslator|MockObject $translator */
        $translator = $this->getMockBuilder(ZendTranslator::class)
            ->setMethods($methods)
            ->getMock();
        return $translator;
    }
}

