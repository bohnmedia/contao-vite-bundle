<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\ContaoManager;

use BohnMedia\ContaoViteBundle\BohnMediaContaoViteBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Contao\ManagerPlugin\Config\ContainerBuilder;
use Contao\ManagerPlugin\Config\ExtensionPluginInterface;
use Contao\ManagerPlugin\Routing\RoutingPluginInterface;
use Exception;
use Pentatrion\ViteBundle\Controller\ProfilerController;
use Pentatrion\ViteBundle\PentatrionViteBundle;
use Symfony\Component\Config\Loader\LoaderResolverInterface;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Yaml\Yaml;

class Plugin implements BundlePluginInterface, RoutingPluginInterface, ExtensionPluginInterface
{
    /**
     * The Vite build directory, mirrored from the pentatrion_vite configuration
     * so the dev-server proxy route uses the same prefix as the rendered assets.
     */
    private string $buildDirectory = 'build';

    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(PentatrionViteBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
            BundleConfig::create(BohnMediaContaoViteBundle::class)
                ->setLoadAfter([PentatrionViteBundle::class]),
        ];
    }

    /**
     * @throws Exception
     */
    public function getRouteCollection(LoaderResolverInterface $resolver, KernelInterface $kernel): ?RouteCollection
    {
        if ('dev' !== $kernel->getEnvironment()) {
            return null;
        }

        $collection = new RouteCollection();

        // Vite dev-server proxy, prefixed with the configured build directory so
        // it matches the URLs of the rendered asset tags (default: /build).
        $proxyResource = '@PentatrionViteBundle/Resources/config/routing.yaml';

        if (false !== ($proxyLoader = $resolver->resolve($proxyResource))) {
            $proxy = $proxyLoader->load($proxyResource);
            $proxy->addPrefix('/' . trim($this->buildDirectory, '/'));
            $collection->addCollection($proxy);
        }

        // Symfony profiler panel (independent of the build directory).
        $collection->add('_profiler_vite', new Route(
            '/_profiler/vite',
            ['_controller' => ProfilerController::class . '::info'],
        ));

        return $collection;
    }

    public function getExtensionConfig($extensionName, array $extensionConfigs, ContainerBuilder $container): array
    {
        if ('framework' === $extensionName) {
            $extensionConfigs[] = Yaml::parseFile(__DIR__ . '/../../config/framework.yaml');
        }

        if ('pentatrion_vite' === $extensionName) {
            foreach ($extensionConfigs as $config) {
                if (isset($config['build_directory']) && \is_string($config['build_directory'])) {
                    $this->buildDirectory = $config['build_directory'];
                }
            }
        }

        return $extensionConfigs;
    }
}
