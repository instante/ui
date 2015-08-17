<?php

namespace InstanteTests\Application\UI;

use Instante\Application\UI\Control;
use Latte\Engine;
use Nette\Application\IPresenter;
use Nette\Application\IAResponse;
use Nette\Application\UI\Form;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Caching\Storages\PhpFileStorage;
use Nette\Configurator;
use Nette\Http\charset;
use Nette\Http\expiration;
use Nette\Http\FileUpload;
use Nette\Http\header;
use Nette\Http\HTTP;
use Nette\Http\IRequest;
use Nette\Http\IResponse;
use Nette\Http\mime;
use Nette\Http\name;
use Nette\Http\URL;
use Nette\Http\UrlScript;
use Nette\Http\value;
use Nette\Utils\Html;
use Nette\Utils\Strings;
use NetteModule\MicroPresenter;
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
        $p = new MockPresenter;
        $p->injectPrimary(NULL, NULL, NULL, new MockRequest, new MockResponse, NULL, NULL,
            new TemplateFactory(new LatteFactory(new Engine)));
        return $p;
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

class MockPresenter extends Presenter
{

}

class LatteFactory implements ILatteFactory
{
    private $engine;

    public function __construct(Engine $engine)
    {
        $this->engine = $engine;
    }

    public function create()
    {
        return $this->engine;
    }
}

/**
 * Yuck.
 */
class MockRequest implements IRequest
{
    function getUrl() { return NULL; }

    function getQuery($key = NULL, $default = NULL) { return NULL; }

    function getPost($key = NULL, $default = NULL) { return NULL; }

    function getFile($key) { return NULL; }

    function getFiles() { return NULL; }

    function getCookie($key, $default = NULL) { return NULL; }

    function getCookies() { return NULL; }

    function getMethod() { return NULL; }

    function isMethod($method) { return NULL; }

    function getHeader($header, $default = NULL) { return NULL; }

    function getHeaders() { return NULL; }

    function isSecured() { return NULL; }

    function isAjax() { return NULL; }

    function getRemoteAddress() { return NULL; }

    function getRemoteHost() { return NULL; }

    function getRawBody() { return NULL; }
}

/**
 * Yummy.
 */
class MockResponse implements IResponse
{
    function setCode($code) { return NULL; }

    function getCode() { return NULL; }

    function setHeader($name, $value) { return NULL; }

    function addHeader($name, $value) { return NULL; }

    function setContentType($type, $charset = NULL) { return NULL; }

    function redirect($url, $code = self::S302_FOUND) { return NULL; }

    function setExpiration($seconds) { return NULL; }

    function isSent() { return NULL; }

    function getHeader($header, $default = NULL) { return NULL; }

    function getHeaders() { return NULL; }

    function setCookie(
        $name,
        $value,
        $expire,
        $path = NULL,
        $domain = NULL,
        $secure = NULL,
        $httpOnly = NULL
    )
    {
        return NULL;
    }

    function deleteCookie($name, $path = NULL, $domain = NULL, $secure = NULL) { return NULL; }
}

run(new ControlTest);
