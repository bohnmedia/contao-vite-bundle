# Contao Vite Bundle

A small Contao 5 wrapper around [pentatrion/vite-bundle][pentatrion]. It
registers `PentatrionViteBundle` in Contao Manager Edition and exposes the
most common rendering helpers as Contao insert tags.

## Installation

```bash
composer require bohn-media/contao-vite-bundle
```

The bundle expects Vite to output its build artifacts into `public/build/`
(the default).

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
