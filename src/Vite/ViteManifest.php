<?php

declare(strict_types=1);

namespace BohnMedia\ContaoViteBundle\Vite;

use Pentatrion\ViteBundle\Service\FileAccessor;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Filesystem\Path;

class ViteManifest
{
    /**
     * @param array<string, array<string, mixed>> $configs
     */
    public function __construct(
        #[Autowire(service: 'pentatrion_vite.file_accessor')]
        private readonly FileAccessor $fileAccessor,
        #[Autowire('%pentatrion_vite.default_config%')]
        private readonly string $configName,
        #[Autowire('%pentatrion_vite.configs%')]
        private readonly array $configs,
        #[Autowire('%kernel.project_dir%%pentatrion_vite.public_directory%')]
        private readonly string $publicPath,
    ) {
    }

    public function resolveUrl(string $sourcePath): ?string
    {
        $entry = $this->entry($sourcePath);

        if (null === $entry) {
            return null;
        }

        return $this->base().$entry['file'];
    }

    public function resolveFilesystemPath(string $sourcePath): ?string
    {
        $entry = $this->entry($sourcePath);

        if (null === $entry) {
            return null;
        }

        return Path::join($this->publicPath, $this->base(), $entry['file']);
    }

    private function base(): string
    {
        return $this->configs[$this->configName]['base'] ?? '/';
    }

    /**
     * @return array<string, mixed>|null
     */
    private function entry(string $sourcePath): ?array
    {
        try {
            $manifest = $this->fileAccessor->getData($this->configName, FileAccessor::MANIFEST);
        } catch (\Throwable) {
            return null;
        }

        $key = Path::normalize($sourcePath);

        return $manifest[$key] ?? null;
    }
}
