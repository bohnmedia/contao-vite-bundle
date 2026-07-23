<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;

#[AsInsertTag('vite_entry_script_tags')]
readonly class ViteEntryScriptTagsInsertTag extends AbstractEntryTagsInsertTag
{
    protected function render(string $entryName, ?string $configName): string
    {
        return $this->entrypointRenderer->renderScripts($entryName, [], $configName);
    }
}
