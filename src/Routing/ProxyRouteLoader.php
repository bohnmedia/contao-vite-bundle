<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\Routing;

use Pentatrion\ViteBundle\Controller\ProfilerController;
use Pentatrion\ViteBundle\Controller\ViteController;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Registers one Vite dev-server proxy route per pentatrion_vite config,
 * prefixed with the respective build directory so it matches the URLs of the
 * rendered asset tags. Reading the resolved configs from the container keeps
 * the routes in sync with the configuration, no matter which cache is rebuilt.
 */
class ProxyRouteLoader extends Loader
{
    public const TYPE = 'bohnmedia_contao_vite_proxy';

    /**
     * @param array<string, array{base: string}> $configs
     */
    public function __construct(private readonly array $configs)
    {
        parent::__construct();
    }

    public function load(mixed $resource, ?string $type = null): RouteCollection
    {
        $collection = new RouteCollection();

        foreach ($this->configs as $configName => $config) {
            $collection->add(
                'pentatrion_vite_build_proxy_' . $configName,
                new Route(
                    $config['base'] . '{path}',
                    [
                        '_controller' => ViteController::class . '::proxyBuild',
                        'configName' => $configName,
                    ],
                    ['path' => '.+'],
                ),
            );
        }

        // Symfony profiler panel (independent of the build directories).
        $collection->add('_profiler_vite', new Route(
            '/_profiler/vite',
            ['_controller' => ProfilerController::class . '::info'],
        ));

        return $collection;
    }

    public function supports(mixed $resource, ?string $type = null): bool
    {
        return self::TYPE === $type;
    }
}
