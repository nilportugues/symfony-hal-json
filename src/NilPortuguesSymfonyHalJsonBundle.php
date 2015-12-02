<?php

namespace NilPortugues\Symfony\HalJsonBundle;

use NilPortugues\Symfony\HalJsonBundle\DependencyInjection\NilPortuguesSymfonyHalJsonExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NilPortuguesSymfonyHalJsonBundle extends Bundle
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new NilPortuguesSymfonyHalJsonExtension();
    }
}
