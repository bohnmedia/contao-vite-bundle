# Contao Vite Bundle

Contao 5 insert tags that resolve Vite-hashed assets via `manifest.json` and
render them through Contao's image pipeline. The result is a plain `<img>` for
simple assets like logos or icons, or a full responsive `<picture>` when the
referenced image size produces multiple sources.

This bundle depends on [pentatrion/vite-bundle][pentatrion] and forwards its
`build_directory` automatically — no extra pentatrion configuration needed.

## Installation

```bash
composer require bohn-media/contao-vite-bundle
```

The bundle's Contao Manager Plugin registers `PentatrionViteBundle`
automatically.

## Configuration

Set the Vite output folder below `public/` in `config/config.yaml`:

```yaml
bohn_media_contao_vite:
  build_directory: vite
```

The value is forwarded internally to `pentatrion_vite.build_directory`.

> **Heads up:** `public/assets/` is symlinked to Contao's Composer components
> directory by default (`extra.contao-component-dir`). Pick a name like `vite`
> or `build` that does not collide with existing Contao conventions.

## Insert Tags

### `{{vite_asset::<path>}}`

Returns the URL of an asset.

```html
<link rel="icon" href="{{vite_asset::vite/images/favicon.svg}}">
```

### `{{vite_image::<path>[?params]}}`

Renders an `<img>` tag with the resolved Vite asset URL plus width, height and
alt attributes. If the optional `size` parameter points to an image size alias
that defines multiple formats, densities or breakpoints, the output is wrapped
in a full responsive `<picture>` element with `<source>` tags.

| Parameter  | Example                            | Description                                            |
|------------|------------------------------------|--------------------------------------------------------|
| `alt`      | `alt=Logo`                         | Alt text                                               |
| `class`    | `class=c-logo__img`                | CSS class                                              |
| `size`     | `size=_nav_main_image` or `size=200` | Image size alias from `contao.image.sizes` or ID       |
| `template` | `template=picture_default`         | Custom picture template (only `a-z0-9_` allowed)       |

```html
{{vite_image::vite/images/logo.svg?alt=Logo}}
{{vite_image::vite/navigation/hero.jpg?size=_nav_main_image&alt=Hero}}
```

## Entry points (JS/CSS)

For Vite entry points (`<script type="module">`, `<link rel="stylesheet">`)
use pentatrion's own Twig functions inside your templates:

```twig
{{ vite_entry_link_tags('app') }}
{{ vite_entry_script_tags('app') }}
```

See the [pentatrion/vite-bundle documentation][pentatrion] for details.

## Manifest paths

Manifest keys are passed through unchanged, exactly as Vite writes them —
including the Vite root folder. See `public/<build_directory>/.vite/manifest.json`.

## License

MIT.

[pentatrion]: https://github.com/lhapaipai/vite-bundle
