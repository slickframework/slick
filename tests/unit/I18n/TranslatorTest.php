<?php

/**
 * Translator test case
 *
 * @package    Test\I18n
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 * @copyright  2014 Filipe Silva
 * @license    http://www.opensource.org/licenses/mit-license.php MIT License
 * @since      Version 1.0.0
 */

namespace I18n;

use Slick\Configuration\Configuration;
use Slick\I18n\TranslateMethods;
use Slick\I18n\Translator;
use Slick\Template\Template;

/**
 * Translator test case
 *
 * @package    Test\I18n
 * @author     Filipe Silva <silvam.filipe@gmail.com>
 */
class TranslatorTest extends \Codeception\TestCase\Test
{

    /**
     * Translate messages
     * @test
     */
    public function translateMessages()
    {
        Configuration::addPath(dirname(dirname(__DIR__)).'/app/Configuration');

        $translator = Translator::getInstance();
        $translator->getTranslatorService()
            ->addTranslationFile('phparray', dirname(__FILE__).'/messages.php');
        $myTranslator = new MyTranslator();
        $this->assertEquals('test', $myTranslator->translate('test'));
        $this->assertEquals('Olá mundo', $myTranslator->translate('Hello world'));
        $this->assertEquals('utilizador', $myTranslator->translatePlural('user', 'users', 1));
        $this->assertEquals('utilizadores', $myTranslator->translatePlural('user', 'users', 2));

        $translator->setLocale('pt_PT');
        $this->assertEquals('pt_PT', $translator->getConfiguration()->get('i18n.locale'));

        Template::addPath(__DIR__);
        $template = new Template(['engine' => 'twig']);
        $template = $template->initialize();
        $template->parse('translatePlural.twig');
        $this->assertEquals('users', $template->process());
    }
}

class MyTranslator
{
    use TranslateMethods;
}