<?php

declare(strict_types=1);

namespace BohnMedia\ViteContaoBundle;

use BohnMedia\ViteContaoBundle\DependencyInjection\BohnMediaViteContaoExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BohnMediaViteContaoBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BohnMediaViteContaoExtension();
    }
}
