<?php

namespace InstanteTests\Application\UI;

use Instante\Application\UI\Control;
use Tester\Assert;
use Tester\TestCase;

require_once __DIR__ . '/../../bootstrap.php';

class ControlTest extends TestCase
{
    public function testBeforeMethod()
    {
        $c = new FooControl;
        $p = $this->createMockPresenter();
        $p->addComponent($c, 'c');
        $c->renderBar('x');
        Assert::true($c->barRendered, "beforeRender* method called");
        Assert::equal('x', $c->arg, "argument to beforeRender* passed");
    }

    public function testAutoFindTemplate()
    {
        $c = new FooControl;
        $p = $this->createMockPresenter();
        $p->addComponent($c, 'c');

        ob_start();
        $c->render();
        $o = ob_get_clean();
        Assert::equal("FooDefault\n", $o, 'template <Control>.latte found');

        ob_start();
        $c->renderBar();
        $o = ob_get_clean();
        Assert::equal("FooBar\n", $o, 'template <Control>.<View>.latte found');

        $b = new BarControl;
        $p->addComponent($b, 'b');

        ob_start();
        $b->renderBazzy();
        $o = ob_get_clean();
        Assert::equal("BarBazzy\n", $o, 'template <Control>/<View>.latte found');

        ob_start();
        $b->renderDefault();
        $o = ob_get_clean();
        Assert::equal("BarDefault\n", $o, 'template <Control>/default.latte found');
    }

    private function createMockPresenter()
    {
        return MockPresenter::create();
    }
}

class BarControl extends Control
{
}

class FooControl extends Control
{
    public $barRendered = FALSE;
    public $arg;

    public function beforeRenderBar($arg = NULL)
    {
        $this->barRendered = TRUE;
        $this->arg = $arg;
    }
}

require_once __DIR__ . '/mocks.php';

run(new ControlTest);
