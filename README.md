# Contao Vite Bundle

A small Contao 5 wrapper around [pentatrion/vite-bundle][pentatrion]. It
registers `PentatrionViteBundle` in Contao Manager Edition so its Twig
functions work out of the box, and additionally exposes the most common
rendering helpers as Contao insert tags for use outside of templates.

## Installation

```bash
composer require bohnmedia/contao-vite-bundle
```

By default the bundle expects Vite to output its build artifacts into
`public/build/`. Both parts of that path can be overridden through the
underlying `pentatrion_vite` configuration:

```yaml
# config/config.yaml
pentatrion_vite:
    public_directory: public   # default: public
    build_directory: build     # default: build
```

See the [pentatrion/vite-bundle reference][reference] for all available
configuration options.

## Rendering entry tags

Render the link and script tags for a Vite entry.

### Twig

```twig
{{ vite_entry_link_tags('app') }}
{{ vite_entry_script_tags('app') }}
```

### Insert tag

```text
{{vite_entry_link_tags::app}}
{{vite_entry_script_tags::app}}
```

The insert tags behave like the Twig functions they wrap: an unknown
entry resolves to an empty string by default, unless you enable
`throw_on_missing_entry` in the configuration.

## Resolving individual asset URLs

A named asset package `vite` is registered automatically, so Vite-hashed
assets resolve via Symfony's `asset()` function or Contao's `{{asset::}}`
insert tag.

### Twig

```twig
{{ asset('@/images/favicon.svg', 'vite') }}
```

### Insert tag

```text
{{asset::@/images/favicon.svg::vite}}
```

## License

MIT.

[pentatrion]: https://github.com/lhapaipai/vite-bundle
[reference]: https://symfony-vite.pentatrion.com/reference/vite-bundle.html
