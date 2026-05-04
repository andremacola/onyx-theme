<p align="center">
	<img width="457" height="211" src="https://andremacola.github.io/onyx-theme-doc/img/logo.png">
</p>
<p align="center">
	<a href="https://andremacola.github.io/onyx-theme-doc/en/"><u>Documentation (EN)</u></a> | 
	<a href="https://andremacola.github.io/onyx-theme-doc/"><u>Documentation (PT-BR)</u></a>
</p>

*Note:* The documentation **needs to be updated**. Some features have been removed to make the theme lighter.

# Wiki

> **THE PUBLIC RELEASE IS IN BETA VERSION.**  
> **WE ALREADY USE THIS VERSION IN PRODUCTION BUT USE WITH CAUTION.**

Onyx Theme is a starter kit for for assistance in developing themes in Wordpress. It can be considered a kind of framework.

This theme is inpired by [Sage](https://github.com/roots/sage/) and [Themosis](https://www.themosis.com/) but ligther because we do not need thousands of dependencies to develop a WordPress theme.

<!-- Better used with [Onyx Starter Kit](https://github.com/andremacola/wp-onyx-starter) (members only) -->

## [Documentation](https://andremacola.github.io/onyx-theme-doc/)

The full documentation is [available here ](https://andremacola.github.io/onyx-theme-doc/).

## How to Install

Download latest version from [releases](https://github.com/andremacola/onyx-theme/releases) extract in the themes folder, then:

- ***1*** / Run `npm install`
- ***2*** / Run `composer install`
- ***3*** / Rename `.env.example` file inside the onyx theme folder to `.env` and configure it
- ***4*** / Activate the theme inside WordPress
- ***5*** / Run `npm run dev` (Vite dev server with HMR)

## Folder structure

- **`core/`** - This is where the main structure of the theme resides
  - `./app` - Classes for theme setup
    - `./Api` - WP REST Api Controllers
    - `./Controllers` - Main Controllers
    - `./Onyx` - Onyx Classes
  - `./config` - Resides all the main configurations of your project.
  - `./includes` - Its own hooks, functions and classes.

- **`src/`** - Source files for styles and javascripts.
  - `./sass`
  - `./js`

- **`views/`** - Twig templates

## Composer

- `composer install` inside theme folder to create the autoload and install dependencies
- `composer onyx-dump` inside theme folder to dump the autoload

## Vite

Vite drives the asset pipeline (SCSS, JS, HMR). To be used in conjunction with a WEB server such as LocalWP, MAMP, or any local WordPress setup.

  - Configure the environment through the file **.env** inside the theme folder (`VITE_DEV_PORT`, `VITE_HTTPS`, `VITE_KEY`, `VITE_CRT`).
  - Execute `npm install` inside the theme folder.

|Commands         | Functionality                                                                              |
|-----------------|--------------------------------------------------------------------------------------------|
| npm run dev     | Start the Vite dev server with HMR (CSS hot-replace, full reload on `.twig`/`.php` saves)  |
| npm run build   | Production build → `assets/dist/` with hashed filenames + `manifest.json` (PurgeCSS + px2rem applied here) |
| npm run preview | Preview the production build locally                                                       |

The PHP layer (`\Onyx\Vite`) detects whether the dev server is running by looking for `assets/dist/hot`; entries declared in `core/config/assets.php` are source paths (e.g. `src/sass/style.scss`) and get resolved to the right URL automatically in dev or production.

## Requiring dependencies in javascript

Please, use ES6 Modules or CommonJS to import your dependencies (see app.js)

## Some JS libraries

|Dependency       | Install                     | Repository/Site                             |
|-----------------|-----------------------------|---------------------------------------------|
tiny-slider       | npm install tiny-slider     | https://github.com/ganlanyuan/tiny-slider   |
imask             | npm install imask           | https://github.com/uNmAnNeR/imaskjs         |
fancybox          | npm install @fancyapps/ui   | https://fancyapps.com                       |
lightgallery.js   | npm install lightgallery    | https://www.lightgalleryjs.com/             |

## License

The Onyx Theme is open-source software licensed under [GPL-2+ license](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
