<?php

declare(strict_types=1);

namespace BohnMedia\ViteContaoBundle\InsertTag;

use BohnMedia\ViteContaoBundle\Vite\ViteManifest;
use Contao\CoreBundle\Asset\ContaoContext;
use Contao\CoreBundle\DependencyInjection\Attribute\AsInsertTag;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\CoreBundle\Image\PictureFactoryInterface;
use Contao\CoreBundle\InsertTag\InsertTagResult;
use Contao\CoreBundle\InsertTag\OutputType;
use Contao\CoreBundle\InsertTag\ResolvedInsertTag;
use Contao\FrontendTemplate;
use Contao\StringUtil;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class ViteImageInsertTag
{
    public function __construct(
        private readonly ViteManifest $manifest,
        private readonly PictureFactoryInterface $pictureFactory,
        private readonly ContaoFramework $framework,
        #[Autowire(service: 'contao.assets.files_context')]
        private readonly ContaoContext $filesContext,
        #[Autowire('%contao.web_dir%')]
        private readonly string $webDir,
    ) {
    }

    #[AsInsertTag('vite_image')]
    public function __invoke(ResolvedInsertTag $insertTag): InsertTagResult
    {
        $raw = StringUtil::decodeEntities((string)$insertTag->getParameters()->get(0));
        $parts = parse_url($raw);
        $source = $parts['path'] ?? $raw;
        $params = [];

        if (isset($parts['query'])) {
            parse_str($parts['query'], $params);
        }

        $alt = is_string($params['alt'] ?? null) ? $params['alt'] : '';
        $class = is_string($params['class'] ?? null) ? $params['class'] : '';
        $template = is_string($params['template'] ?? null)
            ? (string) preg_replace('/[^a-z0-9_]/i', '', $params['template'])
            : 'picture_default';
        $size = match (true) {
            !is_string($params['size'] ?? null) => null,
            is_numeric($params['size']) => (int) $params['size'],
            default => $params['size'],
        };

        $path = $this->manifest->resolveFilesystemPath($source);

        if (null === $path) {
            return new InsertTagResult('', OutputType::html);
        }

        $this->framework->initialize();

        try {
            $picture = $this->pictureFactory->create($path, $size);
            $staticUrl = $this->filesContext->getStaticUrl();

            $data = [
                'img' => $picture->getImg($this->webDir, $staticUrl),
                'sources' => $picture->getSources($this->webDir, $staticUrl),
                'alt' => StringUtil::specialcharsAttribute($alt),
                'class' => StringUtil::specialcharsAttribute($class),
            ];

            $frontendTemplate = new FrontendTemplate($template);
            $frontendTemplate->setData($data);

            return new InsertTagResult($frontendTemplate->parse(), OutputType::html);
        } catch (\Throwable) {
            return new InsertTagResult('', OutputType::html);
        }
    }
}
