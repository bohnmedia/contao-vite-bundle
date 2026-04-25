<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('bohn_media_contao_vite');

        $treeBuilder->getRootNode()
            ->children()
                ->scalarNode('build_directory')
                    ->isRequired()
                    ->cannotBeEmpty()
                    ->info('Output folder under public/ where Vite writes its bundle and manifest.')
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
