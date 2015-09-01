<?php

namespace Instante\Forms\Controls;

use Nette\Forms\Controls\TextInput;
use Nette\Utils\Html;

/**
 * PHP counterpart for instante/ui/displayablePassword, which has to be loaded on pages where this module is used.
 * Use this form control to create a password input with a button revealing the password on held mouse button / touch.
 *
 * TODO automatically add the module to js dependencies container
 */
class DisplayablePassword extends TextInput
{

    /** @var Html */
    private $fullControl;

    function __construct($label = NULL, $maxLength = NULL)
    {
        parent::__construct($label, $maxLength);
        $this->setType('password');
        $this->control->addClass('form-control');
        $this->fullControl = Html::el('span')
            ->addClass('input-group')
            ->add('<span class="input-group-btn"><span class="btn btn-default"><i class="glyphicon glyphicon-eye-open"></i></span></span>')
            ->addAttributes(['data-instante-displayable-password' => TRUE]);
    }

    /**
     * Get control prototype of the initial TextInput
     *
     * @return Html
     */
    public function getInputPrototype()
    {
        return parent::getControlPrototype();
    }

    /**
     * @inheritdoc
     * @return Html
     */
    public function getControlPrototype()
    {
        return $this->fullControl;
    }

    /**
     * @inheritdoc
     * @return Html
     */
    public function getControl()
    {
        $control = clone $this->fullControl;
        $control->insert(0, parent::getControl()); // the original text input control is injected only there
        return $control;
    }
}
