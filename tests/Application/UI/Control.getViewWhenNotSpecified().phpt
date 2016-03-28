<?php

namespace InstanteTests\Application\UI;

use Instante\Application\UI\Control;
use Tester\Assert;

require_once __DIR__ . '/../../bootstrap.php';

require_once __DIR__ . '/mocks.php';

function assertRenderedDefaultProperly($o, $c)
{
    Assert::same("the default\n", $o, 'rendered default template');
    Assert::true($c->renderedImplicitDefault, 'beforeRender() method called');
    Assert::same('DEFAULT', $c->rendered, 'beforeRenderDefault() method called');
}

function assertRenderedOtherProperly($o, $c)
{
    Assert::same("the other\n", $o, 'rendered other template');
    Assert::false($c->renderedImplicitDefault, 'beforeRender() method not called');
    Assert::same('OTHER', $c->rendered, 'beforeRenderOther() method called');
}


class ViewControl extends Control
{
    public $rendered;
    public $renderedImplicitDefault = FALSE;

    public function reset()
    {
        $this->rendered = NULL;
        $this->renderedImplicitDefault = FALSE;
        $this->loadState([]);
    }

    protected function beforeRender()
    {
        $this->renderedImplicitDefault = TRUE;
    }

    protected function beforeRenderDefault()
    {
        $this->rendered = 'DEFAULT';
    }

    protected function beforeRenderOther()
    {
        $this->rendered = 'OTHER';
    }
}


$c = new ViewControl;
$p = MockPresenter::create();
$p->addComponent($c, 'c');

//test view specified by method name
//renderDefault()
ob_start();
$c->renderDefault();
$o = ob_get_clean();
assertRenderedDefaultProperly($o, $c);

//render()
$c->reset();
ob_start();
$c->render();
$o = ob_get_clean();
assertRenderedDefaultProperly($o, $c);

//renderDefault() view=>other (should be overridden)
$c->reset();
ob_start();
$c->renderDefault(['view' => 'other']);
$o = ob_get_clean();
assertRenderedDefaultProperly($o, $c);

//renderOther() view=>default (should be overridden)
$c->reset();
ob_start();
$c->renderOther(['view' => 'default']);
$o = ob_get_clean();
assertRenderedOtherProperly($o, $c);


//test selecting view by component argument
//render() view=>other
$c->reset();
ob_start();
$c->loadState(['view' => 'other']);
$c->render();
$o = ob_get_clean();
assertRenderedOtherProperly($o, $c);

//render() view=>default
$c->reset();
ob_start();
$c->loadState(['view' => 'default']);
$c->render();
$o = ob_get_clean();
assertRenderedDefaultProperly($o, $c);

