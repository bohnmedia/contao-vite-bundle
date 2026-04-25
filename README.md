# Contao Vite Bundle

A tiny Contao 5 wrapper around [pentatrion/vite-bundle][pentatrion]. Its only
job is registering `PentatrionViteBundle` in Contao Manager Edition so its
configuration and Twig functions are available without touching `bundles.php`.

## Installation

```bash
composer require bohn-media/contao-vite-bundle
```

## Configuration

Configure the upstream bundle directly via its native key in
`config/config.yaml`:

```yaml
pentatrion_vite:
  build_directory: build
```

## Usage in templates

```twig
{{ vite_entry_link_tags('app') }}
{{ vite_entry_script_tags('app') }}
```

## Resolving Vite asset URLs via `asset()`

To use Symfony's `asset()` Twig function (or Contao's `{{asset::}}` insert
tag) for Vite-hashed assets, register a named package with pentatrion's
version strategy:

```yaml
# config/packages/framework.yaml
framework:
  assets:
    packages:
      vite:
        version_strategy: 'Pentatrion\ViteBundle\Asset\ViteAssetVersionStrategy'
```

```twig
{{ asset('@/images/favicon.svg', 'vite') }}
```

```html
{{asset::@/images/favicon.svg::vite}}
```

## License

MIT.

[pentatrion]: https://github.com/lhapaipai/vite-bundle
