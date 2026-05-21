<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Pentatrion\ViteBundle\Service\EntrypointRenderer;

#[AsInsertTag('vite_entry_script_tags')]
readonly class ViteEntryScriptTagsInsertTag
{
    public function __construct(private EntrypointRenderer $entrypointRenderer)
    {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $entryName = $insertTag->getParameters()->get(0);

        if (null === $entryName) {
            return new InsertTagResult('', OutputType::html);
        }

        return new InsertTagResult(
            $this->entrypointRenderer->renderScripts($entryName),
            OutputType::html,
        );
    }
}
