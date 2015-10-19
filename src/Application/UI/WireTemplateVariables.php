<?php

/* (c) SecuPlanner contributors 2014 */

namespace Instante\Application\UI;

/**
 * Description of WireTemplateVariables
 *
 * @author Richard Ejem <richard@ejem.cz>
 */
trait WireTemplateVariables
{
    private function wireVars()
    {
        foreach ($this->getReflection()->getProperties() as $property) {
            if ($property->hasAnnotation('template')) {
                $property->setAccessible(TRUE);
                $this->template->{$property->name} = $property->getValue($this);
            }
        }
    }
}
