<?php

namespace InstanteTests\Application\UI;


use Instante\Forms\FormControlHtmlDecorator;
use Nette\Forms\Controls\TextInput;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../bootstrap.php';

class FormControlHtmlDecoratorTest extends TestCase
{
    /**
     * This method is called before a test is executed.
     * @return void
     */
    protected function setUp()
    {
        parent::setUp();
        FormControlHtmlDecorator::register();
    }

    public function testDataFlag()
    {
        $c = new TextInput;
        Assert::false($c->hasDataFlag('foo'), 'missing data flag is missing');
        $c->addDataFlag('foo');
        Assert::true($c->hasDataFlag('foo'), 'data flag was added');
        Assert::true(isset($c->getControlPrototype()->{'data-foo'}), 'data flag is properly bound to HTML');
        $c->removeDataFlag('foo');
        Assert::false($c->hasDataFlag('foo'), 'data flag was removed');
    }
}

(new FormControlHtmlDecoratorTest)->run();
