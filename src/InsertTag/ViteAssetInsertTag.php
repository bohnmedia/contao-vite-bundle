<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use BohnMedia\ContaoViteBundle\Vite\ViteManifest;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;

class ViteAssetInsertTag
{
    public function __construct(private readonly ViteManifest $manifest)
    {
    }

    #[AsInsertTag('vite_asset')]
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $source = (string) $insertTag->getParameters()->get(0);
        $url = $this->manifest->resolveUrl($source);

        return new InsertTagResult($url ?? '', OutputType::url);
    }
}
