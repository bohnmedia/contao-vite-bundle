<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Pentatrion\ViteBundle\Service\EntrypointRenderer;

abstract readonly class AbstractEntryTagsInsertTag
{
    public function __construct(protected EntrypointRenderer $entrypointRenderer)
    {
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $entryName = $insertTag->getParameters()->get(0);

        if (null === $entryName) {
            return new InsertTagResult('', OutputType::html);
        }

        $configName = $insertTag->getParameters()->get(1);

        return new InsertTagResult(
            $this->render($entryName, '' !== $configName ? $configName : null),
            OutputType::html,
        );
    }

    abstract protected function render(string $entryName, ?string $configName): string;
}
