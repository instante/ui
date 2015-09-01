<?php

namespace Instante\Forms;

use Nette\Forms\Controls\BaseControl;

/**
 * Adds methods to Nette form controls:
 * addDataFlag(name), hasDataFlag(name), removeDataFlag(name).
 * setting a data flag means to set no-value data-{name} attribute to the root html element of the control.
 *
 */
class FormControlHtmlDecorator
{
    function __construct() { throw new \Nette\StaticClassException; }

    static function register()
    {
        BaseControl::extensionMethod('addDataFlag', function (BaseControl $that, $name) {
            $that->getControlPrototype()->addAttributes(['data-' . $name => TRUE]);
            return $that;
        });
        BaseControl::extensionMethod('hasDataFlag', function (BaseControl $that, $name) {
            return isset($that->getControlPrototype()->attrs['data-' . $name]);
        });
        BaseControl::extensionMethod('removeDataFlag', function (BaseControl $that, $name) {
            unset($that->getControlPrototype()->attrs['data-' . $name]);
            return $that;
        });
    }
}
