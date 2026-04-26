# Contao Vite Bundle

A tiny Contao 5 wrapper around [pentatrion/vite-bundle][pentatrion]. Its only
job is registering `PentatrionViteBundle` in Contao Manager Edition so its
configuration and Twig functions are available without touching `bundles.php`.

## Installation

```bash
composer require bohn-media/contao-vite-bundle
```

The bundle expects Vite to output its build artifacts into `public/build/`
(the default).

## Usage in templates

```twig
{{ vite_entry_link_tags('app') }}
{{ vite_entry_script_tags('app') }}
```

## Resolving Vite asset URLs via `asset()`

A named asset package `vite` is registered automatically, so you can resolve
Vite-hashed assets via Symfony's `asset()` Twig function or Contao's
`{{asset::}}` insert tag:

```twig
{{ asset('@/images/favicon.svg', 'vite') }}
```

```html
{{asset::@/images/favicon.svg::vite}}
```

## License

MIT.

[pentatrion]: https://github.com/lhapaipai/vite-bundle
