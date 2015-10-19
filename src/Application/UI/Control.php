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
 * @method void render()
 */
class Control extends NControl {
    use WireTemplateVariables;
    public function __call($name, $args) {
        if (Strings::startsWith($name, 'render')) {
            if ($this->getReflection()->hasMethod($method='before'.ucfirst($name))) {
                call_user_func_array([$this, $method], $args);
            }
            $view = lcfirst(substr($name, 6 /* strlen('render')*/));
            $this->finishRendering($view);
        }
        else {
            return parent::__call($name, $args);
        }
    }

    protected function finishRendering($view) {
        $this->wireVars();
        $controlClassName = $this->getReflection()->getShortName();
        $dir = dirname($this->getReflection()->getFileName());
        if ($view === '') $view = 'default';
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
        throw new FileNotFoundException(
            "Missing template file for control $controlClassName, searched in: ".implode(',', $files));
    }
}
