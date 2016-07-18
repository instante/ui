<?php

/* (c) SecuPlanner contributors 2014 */

namespace Instante\Application\UI;

use Nette\Reflection\ClassType;

/**
 * Description of WireTemplateVariables
 *
 * @author Richard Ejem <richard@ejem.cz>
 */
trait WireTemplateVariables
{
    private function wireVars()
    {
        foreach ((new ClassType($this))->getProperties() as $property) {
            if ($property->hasAnnotation('template')) {
                $property->setAccessible(TRUE);
                $this->template->{$property->name} = $property->getValue($this);
            }
        }
    }
}
