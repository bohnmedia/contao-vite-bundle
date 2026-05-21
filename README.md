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

If the entry name is missing or rendering fails (e.g. unknown entry,
broken `entrypoints.json`), the insert tags render as an empty string and
log an error instead of throwing.

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
