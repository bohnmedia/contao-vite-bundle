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
        $raw = (string) $insertTag->getParameters()->get(0);
        $source = $raw;
        $alt = '';
        $class = '';
        $template = 'picture_default';
        $size = null;

        if (str_contains($raw, '?')) {
            [$source, $query] = explode('?', $raw, 2);

            foreach (explode('&', StringUtil::decodeEntities($query)) as $pair) {
                if (!str_contains($pair, '=')) {
                    continue;
                }

                [$key, $value] = explode('=', urldecode($pair), 2);

                switch ($key) {
                    case 'alt':
                        $alt = $value;
                        break;

                    case 'class':
                        $class = $value;
                        break;

                    case 'template':
                        $template = preg_replace('/[^a-z0-9_]/i', '', $value) ?: $template;
                        break;

                    case 'size':
                        $size = is_numeric($value) ? (int) $value : $value;
                        break;
                }
            }
        }

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
