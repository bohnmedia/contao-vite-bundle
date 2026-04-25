<?php

declare(strict_types=1);

namespace BohnMedia\ViteContaoBundle\ContaoManager;

use BohnMedia\ViteContaoBundle\BohnMediaViteContaoBundle;
use Contao\CoreBundle\ContaoCoreBundle;
use Contao\ManagerPlugin\Bundle\BundlePluginInterface;
use Contao\ManagerPlugin\Bundle\Config\BundleConfig;
use Contao\ManagerPlugin\Bundle\Parser\ParserInterface;
use Pentatrion\ViteBundle\PentatrionViteBundle;

class Plugin implements BundlePluginInterface
{
    public function getBundles(ParserInterface $parser): array
    {
        return [
            BundleConfig::create(PentatrionViteBundle::class)
                ->setLoadAfter([ContaoCoreBundle::class]),
            BundleConfig::create(BohnMediaViteContaoBundle::class)
                ->setLoadAfter([PentatrionViteBundle::class]),
        ];
    }
}
