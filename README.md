<p align="center">
	<img width="457" height="211" src="https://andremacola.github.io/onyx-theme-doc/img/logo.png">
</p>
<p align="center">
	<a href="https://andremacola.github.io/onyx-theme-doc/en/"><u>Documentation (EN)</u></a> | 
	<a href="https://andremacola.github.io/onyx-theme-doc/"><u>Documentation (PT-BR)</u></a>
</p>



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

- ***1*** / Run `yarn|npm install`
- ***2*** / Run `composer install`
- ***3*** / Rename `.env.example` file inside the onyx theme folder to `.env` and configure it
- ***4*** / Activate the theme inside WordPress
- ***5*** / Run `yarn serve` or `npm run serve` (live reload will only work if you develop with a `.local` domain)

## Folder structure

- **`core/`** - This is where the main structure of the theme resides
  - `./app` - Classes for theme setup
    - `./Api` - WP REST Api Controllers
    - `./Controllers` - Main Controllers
    - `./Onyx` - Onyx Classes
  - `./config` - Resides all the main configurations of your project.
  - `./includes` - Its own functions and classes.
  - `./lang` - Translations

- **`src/`** - Source files for styles and javascripts.
  - `./sass`
  - `./js`

- **`views/`** - Twig templates

## Composer

- `composer install` inside theme folder to create the autoload and install dependencies
- `composer onyx-dump` inside theme folder to dump the autoload

## Gulp

Gulp is used for processing javascripts, scss and livereload. To be used in conjunction with a WEB server such as LocalbyFlywheel or MAMP.

  - Configure the environment through the file **.env** inside the theme folder.
  - Execute `yarn|npm install` inside theme folder.

|Commands          | Functionality                              |
|------------------|--------------------------------------------|
| yarn serve       | Listen and process files when saving. Need a `.local` domain to work
| yarn serve:prod  | Same as `serve` but using production environment
| yarn build       | Build assets (css/js)

**Other gulp commands**
`yarn gulp styles`, `yarn gulp stylesHome`, `yarn gulp stylesPurge`, `yarn gulp js`, `yarn gulp jsHome`, `yarn gulp watch`

## Requiring dependencies in javascript

Please, use ES6 Modules or CommonJS to import your dependencies (see app.js)

## Some JS libraries

|Dependency        | Install                      | Repository/Site                                                  |
|------------------|------------------------------|------------------------------------------------------------------|
jquery             | yarn add jquery              | https://github.com/jquery/jquery                                 |
jquery-mask-plugin | yarn add jquery-mask-plugin  | https://github.com/igorescobar/jQuery-Mask-Plugin                |
jquery fancybox    | yarn add @fancyapps/fancybox | https://github.com/fancyapps/fancybox                            |
imask              | yarn add imask               | https://github.com/uNmAnNeR/imaskjs                              |
tiny-slider        | yarn add tiny-slider         | https://github.com/ganlanyuan/tiny-slider                        |
lightgallery.js    | yarn add lightgallery.js     | https://sachinchoolur.github.io/lightgallery.js/                 |
lg-thumbnail.js    | yarn add lg-thumbnail.js     | https://sachinchoolur.github.io/lightgallery.js/demos/index.html |
vanilla-lazyload   | yarn add vanilla-lazyload    | https://github.com/verlok/vanilla-lazyload                       |
body-scroll-lock   | yarn add body-scroll-lock    | https://github.com/willmcpo/body-scroll-lock                     |
object-fit-images  | yarn add object-fit-images   | https://github.com/fregante/object-fit-images                    |

## Some projects using Onyx

- [O Imparcial](https://oimparcial.com.br/)
- [Rofe Distribuidora](https://www.rofedistribuidora.com.br/)
- [Linhares Jr](https://linharesjr.com)
- [Grupo Dimensão](http://grupodimensao.com/) (legacy version)
- [Jornal Pequeno](https://jornalpequeno.com.br/) (legacy version)

...and many others

## License

The Onyx Theme is open-source software licensed under [GPL-2+ license](https://www.gnu.org/licenses/old-licenses/gpl-2.0.html).
