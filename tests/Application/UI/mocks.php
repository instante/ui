<?php

namespace InstanteTests\Application\UI;

use Latte\Engine;
use Nette\Application\UI\Presenter;
use Nette\Bridges\ApplicationLatte\ILatteFactory;
use Nette\Bridges\ApplicationLatte\TemplateFactory;
use Nette\Http\IRequest;
use Nette\Http\IResponse;

class MockPresenter extends Presenter
{
    public static function create()
    {
        $p = new MockPresenter;
        $p->injectPrimary(NULL, NULL, NULL, new MockRequest, new MockResponse, NULL, NULL,
            new TemplateFactory(new LatteFactory(new Engine)));
        return $p;
    }
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
    ) {
        return NULL;
    }

    function deleteCookie($name, $path = NULL, $domain = NULL, $secure = NULL) { return NULL; }
}
