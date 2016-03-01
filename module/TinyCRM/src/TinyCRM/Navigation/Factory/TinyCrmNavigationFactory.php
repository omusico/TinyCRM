<?php

namespace TinyCRM\Navigation\Factory;

use Zend\Navigation\Service\DefaultNavigationFactory;

/**
 * Global TinyCRM navigation factory.
 */
class TinyCrmNavigationFactory extends DefaultNavigationFactory
{
    /**
     * @return string
     */
    protected function getName()
    {
        return 'tiny-crm';
    }
}