<?php

/* (c) Instante contributors 2014 */

namespace Instante\Application\UI;

use Nette\Application\UI\Control as NControl;
use Nette\FileNotFoundException;
use Nette\Utils\Strings;

/**
 * Adds cool features to Nette control.
 *
 * Auto-assigns template file named <controlName>.latte, <controlName>.<viewName>.latte
 * or <controlName>/<viewName>.latte .
 * Auto-assigns member variables to control template annotated with \@template annotation.
 *
 * @author Richard Ejem <richard@ejem.cz>
 * @method void render($args = [])
 */
class Control extends NControl
{
    use WireTemplateVariables;

    const DEFAULT_VIEW = 'default';
    const VIEW_ARGUMENT = 'view';

    public function __call($name, $args)
    {
        if (Strings::startsWith($name, 'render')) {
            $this->doRenderView($this->getViewByRenderMethod($name), $args);
        } else {
            return parent::__call($name, $args);
        }
    }

    protected function doRenderView($view, $args)
    {
        $method = 'beforeRender' . ucfirst($view);
        if ($this->getReflection()->hasMethod($method)) {
            call_user_func_array([$this, $method], $args);
        }
        if ($view === static::DEFAULT_VIEW && $this->getReflection()->hasMethod('beforeRender')) {
            call_user_func_array([$this, 'beforeRender'], $args);
        }
        $this->finishRendering($view);
    }

    protected function finishRendering($view)
    {
        $this->wireVars();
        $controlClassName = $this->getReflection()->getShortName();
        $dir = dirname($this->getReflection()->getFileName());

        $files = [
            "$dir/$controlClassName.$view.latte",
            "$dir/$controlClassName/$view.latte",
            "$dir/$controlClassName.latte",
        ];
        foreach ($files as $file) {
            if (is_file($file)) {
                $this->template->setFile($file);
                $this->template->render();
                return;
            }
        }
        throw new FileNotFoundException(sprintf(
            "Missing template file for control %s, searched in: %s",
            $controlClassName,
            implode(',', $files)
        ));
    }

    protected function getViewByRenderMethod($name)
    {
        $view = lcfirst(substr($name, 6 /* strlen('render')*/));
        if ($view === '') {
            $view = $this->getViewWhenNotSpecified();
        }
        return $view;
    }

    protected function getViewWhenNotSpecified()
    {
        if (array_key_exists(static::VIEW_ARGUMENT, $this->params)) {
            return $this->params[static::VIEW_ARGUMENT];
        } else {
            return static::DEFAULT_VIEW;
        }
    }
}
