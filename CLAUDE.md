# ONYX THEME FRAMEWORK

## What this is

Onyx is a WordPress starter theme inspired by Sage/Themosis but lighter. It pairs **Timber/Twig** for views with a **PSR-4 controller layer** that hooks into WordPress's template hierarchy. Most setup is data-driven via `core/config/*.php` files rather than imperative code.

## Common commands

Build/dev (run from theme root):

```
npm install                # install JS deps
composer install           # install PHP deps + generates core/vendor/autoload.php (required — functions.php wp_die's without it)
npm run dev | npm run serve  # gulp watch + livereload (needs a .local domain or localhost)
npm run serve:prod         # serve with NODE_ENV=prod (minified output)
npm run build              # production build (styles → stylesPurge → jsApp → jsAdmin)
composer onyx:dump         # composer dump-autoload -o (after adding new namespaced classes)
```

Targeted gulp tasks: `npx gulp styles`, `npx gulp stylesPurge`, `npx gulp jsApp`, `npx gulp jsAdmin`.

Lint (no tests configured):

```
./core/vendor/bin/phpcs              # WordPress-Extra + WordPress-Docs, see .phpcs.xml for excluded sniffs
./core/vendor/bin/phpcs path/to/file.php
npx eslint src/js                    # WordPress eslint preset, see .eslintrc.cjs
```

`.env` (copy from `.env.example`) controls `LIVERELOAD`, `LIVERELOAD_PORT`, optional `KEY`/`CRT` for HTTPS livereload.

## Architecture

### Bootstrap chain
`functions.php` requires `core/vendor/autoload.php`, then `core/includes/hooks-functions.php`, then initializes Timber with `views/` as the templates dir, then instantiates `\Onyx\Setup` (theme config) and `\Onyx\Boot` (router). Defines `ONYX_THEME` and `ONYX_THEME_VERSION` (random per request when current user is in `ONYX_DEVELOPERS`, for cachebusting).

### Controller routing (`core/app/Onyx/Boot.php`)
Onyx replaces the normal "load a PHP template" flow:
1. Hooks every `*_template_hierarchy` filter to capture WP's candidate template list.
2. On `template_include` (priority 100), walks that list and looks for a matching file under `core/app/Controllers/<NameInPascalCase>Controller.php`.
3. If found, instantiates `\Onyx\Controllers\<Name>Controller` and `die`s — short-circuiting WP's PHP template loader. The Twig render happens inside the controller.
4. Special case: `404` becomes `Error404Controller`.

So **adding a new template means adding a Controller class, not a PHP file in the theme root**. Existing controllers: `Home`, `Page`, `Single`, `Archive`, `Category`, `Search`, `Error404`.

### Base Controller (`core/app/Onyx/Controller.php`)
Every controller extends `\Onyx\Controller` and implements `initialize()`. The constructor pulls `Timber::context()`, calls `initialize()`, then auto-renders `$this->templates` via Timber. Helpers:
- `set_context($key, $val)` / `set_context($array)`, `get_context($key)`
- `set_templates(string|array)` — explicit Twig template list
- `set_page_templates($prefix='default', $folder='pages')` — builds `pages/{prefix}-{ID|post_type|slug}.twig` fallback chain
- `set_archive_templates()` — dispatches to date / post-type / taxonomy variants
- `get_post()`, `get_posts($args)` (returns `Timber\PostQuery`), `set_ignore_posts()`
- `no_render()` to suppress auto-render (e.g., when returning JSON)

### REST controllers (`core/app/Onyx/RestController.php`)
Subclass `\Onyx\RestController`, set `$namespace`, register endpoints in `register_routes()` with `$this->route($method, $route, $callback, $options)`. Use `$this->rest_response($data, $status)` to return. Register the controller class in `core/config/app.php` so `Setup` instantiates it; the constructor must call `$this->initialize()` to hook `rest_api_init`. See `core/app/Api/ExampleRestController.php`.

### Setup (`core/app/Onyx/Setup.php`) and config files
`Setup` extends `Timber\Site`, loads each `core/config/*.php` file via `Helpers::load()` and applies them on `after_setup_theme`:
- `app.php` — array of fully qualified class names (CPTs, taxonomies, hook classes, REST controllers); each is `new`'d. **This is how you wire custom classes into the theme.**
- `support.php` — `add_theme_support()` features
- `images.php` — `add_image_size()` definitions
- `sidebars.php` — sidebars (registered via `\Onyx\Sidebar`)
- `hooks.php` — declarative `add_action`/`remove_action`/`add_filter`/`remove_filter` lists, keyed by `actions.add|remove` and `filters.add|remove|apply`. Format: `[$tag, $function, $priority?, $args?]`.
- `assets.php` — CSS/JS handles, consumed by `onyx_enqueue_assets()`
- `mail.php` — SMTP config for `onyx_smtp_config` (commented-out by default)
- `env.php` — environment-derived values (paths, version, `local`, `devs`, upload limits, Timber cache settings)
- `contexts.php` — array merged into Timber's global context via the `timber/context` filter

### Hooks (`core/includes/hooks-functions.php`)
**Hook _functions_ live here; hook _registrations_ live in `core/config/hooks.php`.** Many functions are intentionally registered at the bottom of this file (e.g., `body_class`, `the_content`, `timber/context`, admin-bar tweaks, ACF tweaks, mime-type filtering, livereload). Suppresses the main WP query on the home page (`onyx_supress_main_query`) — home posts come from the Controller's own `WP_Query`.

### PSR-4 autoload map (`composer.json`)
- `Onyx\` → `core/app/Onyx`
- `Onyx\Controllers\` → both `core/app/Controllers` and `core/app/Api`

After adding new namespaced classes run `composer onyx:dump` (composer-installed; alias for `composer dump-autoload -o`).

### Views
Twig files live in `views/`. `base.twig` is the wrapper; pages typically `{% extends 'base.twig' %}` and override `content`/`head`/`sidebar`/`footer` blocks. Convention: `views/pages/` for top-level templates resolved by `set_page_templates()`/`set_archive_templates()`; `views/partials/` for header/footer/sidebar; `views/blocks/` for reusable fragments. Timber cache is disabled when `wp_get_environment_type() === 'local'`.

### Frontend pipeline (`gulpfile.js`)
- SCSS: `src/sass/style.scss` → `assets/css/style.css`. `npm run build` runs `stylesPurge` (PurgeCSS over `core/**/*.php`, `views/**/*.{twig,php}`, `src/js/**/*.js`) and `px2rem`. Add new dynamic class patterns to `config.purgecss.whitelist` or they'll be stripped in production.
- JS app bundle: `src/js/app/app.js` → `assets/js/app.min.js` (Rollup, CommonJS output, terser when prod).
- JS admin (Gutenberg): `src/js/admin/admin.js` → `assets/js/admin/onyx.min.js`. Enqueued by `onyx_gutenberg_js` with `wp-blocks`/`wp-dom-ready`/`wp-edit-post` deps.
- Livereload: `gulp-livereload` listens on `LIVERELOAD_PORT` (default 3010); only injected for `localhost`/`.local` hosts via `onyx_enqueue_livereload`.

### `Onyx\Helpers` (`core/app/Onyx/Helpers.php`, aliased `O`)
Static helper grab-bag used throughout: `O::conf('env'|'assets'|...)` reads loaded config (with `pass`/`password`/`key`/`keys`/`devs` redacted), `O::load($file)` reads a config file fresh, `O::is_dev()` checks against `ONYX_DEVELOPERS` or `local` env, `O::clear_cache_timber()`, `O::route_type()`, `O::section_title()`, `O::pagenavi()`, `O::menu()`, `O::static_path()`/`O::css()`/`O::js()`/`O::img()` for asset URLs.

## Coding conventions

- PHP follows `WordPress-Extra` + `WordPress-Docs` with the exclusions in `.phpcs.xml` (notably: short array syntax allowed, Yoda conditions not enforced, class filename casing relaxed for PSR-4, function/class doc comments not required).
- JS follows `@wordpress/eslint-plugin/esnext` with single quotes, `no-unused-vars`/`no-console`/`no-var`/`eqeqeq` disabled. `wp`, `onyx`, `$`, `jQuery` are pre-declared globals.
- ESM (`"type": "module"` in `package.json`); `.cjs` is used for CommonJS files like `.eslintrc.cjs`.
- `tests/` is excluded from PHPCS and is loaded only when `ONYX_TESTS` is defined — it currently holds scratch/Router experiments, not a test suite.

## Notes / gotchas

- `core/vendor/` is the composer vendor dir (overridden in `composer.json`). Don't expect a top-level `vendor/`.
- `core/app/stubs/` holds IDE stubs (WordPress, ACF, WP-CLI) — not runtime code.
- The README documents some features (e.g., `onyx-theme-doc` site, `Onyx Starter Kit`) that may be stale; treat the code as the source of truth.
- Documentation (external): https://andremacola.github.io/onyx-theme-doc/ (per the README, marked as "needs to be updated").
