<?php

namespace NilPortugues\Symfony2\HalJsonBundle;

use NilPortugues\Symfony2\HalJsonBundle\DependencyInjection\NilPortuguesSymfony2HalJsonExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class NilPortuguesSymfony2HalJsonBundle extends Bundle
{
    /**
     * @return \Symfony\Component\DependencyInjection\Extension\ExtensionInterface
     */
    public function getContainerExtension()
    {
        return new NilPortuguesSymfony2HalJsonExtension();
    }
}
