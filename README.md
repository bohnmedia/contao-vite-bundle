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

In the `dev` environment the Vite dev-server proxy routes and the Symfony
profiler panel are registered automatically — one proxy route per configured
build directory, so no manual routing setup is needed.

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

## Multiple Vite configs

Multiple builds via the `configs` option of pentatrion/vite-bundle are
supported as well:

```yaml
# config/config.yaml
pentatrion_vite:
    default_config: app
    configs:
        app:
            build_directory: build
        admin:
            build_directory: build-admin
```

A dev-server proxy route is registered for each config. To render the tags
of a config other than the default one, pass its name as the `configName`
argument of the Twig functions or as the second insert tag parameter:

### Twig

```twig
{{ vite_entry_link_tags('app', [], 'admin') }}
{{ vite_entry_script_tags('app', [], 'admin') }}
```

### Insert tag

```text
{{vite_entry_link_tags::app::admin}}
{{vite_entry_script_tags::app::admin}}
```

The automatically registered `vite` asset package is bound to the default
config. To resolve assets from another config, register an additional
package with its own version strategy — the same approach the
[pentatrion/vite-bundle documentation][reference] describes:

```yaml
# config/services.yaml
services:
    app.vite_asset_strategy.admin:
        class: Pentatrion\ViteBundle\Asset\ViteAssetVersionStrategy
        arguments:
            - '@pentatrion_vite.file_accessor'
            - 'admin'
            - '%pentatrion_vite.absolute_url%'
            - '@?request_stack'
            - '%pentatrion_vite.throw_on_missing_asset%'
```

```yaml
# config/config.yaml
framework:
    assets:
        packages:
            vite_admin:
                version_strategy: 'app.vite_asset_strategy.admin'
```

```text
{{asset::@/images/favicon.svg::vite_admin}}
```

## License

MIT.

[pentatrion]: https://github.com/lhapaipai/vite-bundle
[reference]: https://symfony-vite.pentatrion.com/reference/vite-bundle.html
