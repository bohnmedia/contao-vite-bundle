<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;

#[AsInsertTag('vite_entry_link_tags')]
readonly class ViteEntryLinkTagsInsertTag extends AbstractEntryTagsInsertTag
{
    protected function render(string $entryName, ?string $configName): string
    {
        return $this->entrypointRenderer->renderLinks($entryName, [], $configName);
    }
}
