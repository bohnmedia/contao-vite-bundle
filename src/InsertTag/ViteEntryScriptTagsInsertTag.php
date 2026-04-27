<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\InsertTag;

use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Pentatrion\ViteBundle\Service\EntrypointRenderer;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Throwable;
use function sprintf;

#[AsInsertTag('vite_entry_script_tags')]
readonly class ViteEntryScriptTagsInsertTag
{
    private LoggerInterface $logger;

    public function __construct(
        private EntrypointRenderer $entrypointRenderer,
        LoggerInterface|null $logger = null,
    ) {
        $this->logger = $logger ?? new NullLogger();
    }

    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $entryName = $insertTag->getParameters()->get(0);

        if (null === $entryName) {
            return new InsertTagResult('', OutputType::html);
        }

        try {
            return new InsertTagResult(
                $this->entrypointRenderer->renderScripts($entryName),
                OutputType::html,
            );
        } catch (Throwable $exception) {
            $this->logger->error(
                sprintf('Failed to render vite_entry_script_tags for entry "%s": %s', $entryName, $exception->getMessage()),
                ['exception' => $exception],
            );

            return new InsertTagResult('', OutputType::html);
        }
    }
}
