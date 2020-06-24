# Wiki

Onyx Theme is a starter kit for for assistance in developing themes in Wordpress. It can be considered a kind of framework.

This theme is inpired by [Sage](https://github.com/roots/sage/) and [Themosis](https://www.themosis.com/) but uncomplicated because we do not need thousands of dependencies to develop a wordpress theme.

<!-- Better used with [Onyx Starter Kit](https://github.com/andremacola/wp-onyx-starter) (members only) -->

WIKI IN DEVELOPMENT...

## Folder structure

- **`core/`** - is where all the main structure of the theme resides
	- `./api`
	- `./app`
	- `./config`

- **`src/`** - source files for styles and javascripts
	- `./sass`
	- `./js`

- **`templates/`** - template part folders
- **`views/`** - wordpress main files

## Composer

  - `composer onyx-dump` inside theme folder to create the autoload

## Gulp

Gulp is used for processing javascripts, scss and livereload. To be used in conjunction with a WEB server such as LocalbyFlywheel or MAMP.

  - Configurar o ambiente pelo arquivo **.env** dentro da
   pasta do tema.
  - Configure the environment through the file **.env** inside the theme folder.
  - Execute `yarn|npm install` inside theme folder.

|Commands      | Functionality                              |
|--------------|--------------------------------------------|
|gulp watch    | Listen and process files when saving
|gulp server   | Uses BrowserSync to serve
|gulp live     | Uses LiveReload to serve (recommended). Need a `.local` domain to work

**Other commands**
`styleMain`, `styleInt`, `purgecss`, `jsMain`, `jsInt`

## Requiring dependencies in javascript

For compatibility reasons with old projects, we do not use `import/require`

Add on top of `app.js` => `//=require "path/of/script.js"`

## Optional libraries

|Dependency        | Install                      | Repository                                        |
|------------------|------------------------------|---------------------------------------------------|
jquery             | yarn add jquery              | https://github.com/jquery/jquery                  |
jquery-mask-plugin | yarn add jquery-mask-plugin  | https://github.com/igorescobar/jQuery-Mask-Plugin |
jquery fancybox    | yarn add @fancyapps/fancybox | https://github.com/fancyapps/fancybox             |
tiny-slider        | yarn add tiny-slider         | https://github.com/ganlanyuan/tiny-slider         |
vanilla-lazyload   | yarn add vanilla-lazyload    | https://github.com/verlok/vanilla-lazyload        |
body-scroll-lock   | yarn add body-scroll-lock    | https://github.com/willmcpo/body-scroll-lock      |
object-fit-images  | yarn add object-fit-images   | https://github.com/fregante/object-fit-images     |

