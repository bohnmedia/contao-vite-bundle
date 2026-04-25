<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\ContaoManager;

use BohnMedia\ContaoViteBundle\BohnMediaContaoViteBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Pentatrion\ViteBundle\PentatrionViteBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\RouteCollection;

class Plugin implements BundlePluginInterface, RoutingPluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(PentatrionViteBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
            BundleConfig::create(BohnMediaContaoViteBundle::class)
                ->setLoadAfter([PentatrionViteBundle::class]),
        ];
    }

    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel): ?RouteCollection
    {
        if ('dev' !== $kernel->getEnvironment()) {
            return null;
        }

        $file = __DIR__ . '/../../config/routes_dev.yaml';
        $loader = $resolver->resolve($file);

        if (false === $loader) {
            return null;
        }

        return $loader->load($file);
    }
}
