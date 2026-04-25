<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle;

use BohnMedia\ContaoViteBundle\DependencyInjection\BohnMediaContaoViteExtension;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BohnMediaContaoViteBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new BohnMediaContaoViteExtension();
    }
}
