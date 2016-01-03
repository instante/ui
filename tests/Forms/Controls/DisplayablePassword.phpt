<?php

namespace InstanteTests\Application\UI;

use Instante\Forms\Controls\DisplayablePassword;
use Nette\Application\UI\Form;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

class DisplayablePasswordTest extends TestCase
{
    public function testControlPrototype()
    {
        $c = new DisplayablePassword;
        Assert::true(isset($c->getControlPrototype()->{'data-instante-displayable-password'}), 'the control has a proper data attribute flag');
        Assert::same('password', $c->getInputPrototype()->type, 'Type of the control is "password"');
    }

    public function testInputPrototype()
    {
        $c = new DisplayablePassword;
        Assert::equal('input', $c->getInputPrototype()->getName(), 'access to original text input control prototype');
    }

    public function testGetControl()
    {
        $c = new DisplayablePassword;
        $f = new Form;
        $f->addComponent($c, 'foo'); //attaching to form required when calling getControl()
        $control = $c->getControl();
        Assert::same('input', $control->getChildren()[0]->getName(), 'access to original text input via getControl()');
        Assert::match('~\binput-group-btn\b~', $control->getChildren()[1], 'properly built full control clone via getControl()');
    }
}

run(new DisplayablePasswordTest);
