<?php

namespace InstanteTests\Application\UI;

use Instante\Application\UI\WireTemplateVariables;
use Nette\Object;
use stdClass;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';


class Foo extends Object
{
    use WireTemplateVariables;

    public $template;

    /** @template */
    private $foo = 'Foo';

    /** @template */
    protected $bar = 'Bar';

    /** @template */
    public $baz = 'Baz';

    public function wire()
    {
        $this->template = new stdClass;
        $this->wireVars();
    }
}

class WireTemplateVariablesTest extends TestCase
{
    public function testWire()
    {
        $x = new Foo;
        $x->wire();
        Assert::equal('Foo', $x->template->foo);
        Assert::equal('Bar', $x->template->bar);
        Assert::equal('Baz', $x->template->baz);
    }
}


run(new WireTemplateVariablesTest());




