# Wiki

Onyx Theme é um starter kit para auxílio no desenvolvimento de temas em Wordpress.

## Estrutura de pastas

- **`core/`** é onde reside toda a estrutura principal do tema
	- `./api`
	- `./app`
	- `./config`
	- `./types`
	- `./taxs`

- **`src/`** arquivos fontes de estilos e javascripts
	- `./sass`
	- `./js`

- **`templates/`** --
- **`views/`** --

## Gulp

O Gulp é utilizado para processamento de javascripts, scss e livereload. Para ser utilizado junto com um servidor WEB como o LocalbyFlywheel ou MAMP.

  - Configurar o ambiente pelo arquivo **.env** dentro da
   pasta do tema.
  - Executar `yarn install` dentro da pasta do tema.

|Comandos      | Funcionalidade                             |
|--------------|--------------------------------------------|
|gulp watch    | Escuta e processa os arquivos ao salvar
|gulp server   | Utiliza o BrowserSync para servir
|gulp live     | Utiliza o LiveReload para servir (recomendado)

**Outros comandos**
`styleMain`, `styleInt`, `purgecss`, `jsMain`, `jsInt`

## Fazendo require de dependencias no javascript

Adicionar no topo do `app.js` `//=require "path/of/script.js"`

## Bibliotecas opcionais

|Dependência       | Instalar                     | Repo                                              |
|------------------|------------------------------|---------------------------------------------------|
jquery             | yarn add jquery              | https://github.com/jquery/jquery                  |
jquery-mask-plugin | yarn add jquery-mask-plugin  | https://github.com/igorescobar/jQuery-Mask-Plugin |
jquery fancybox    | yarn add @fancyapps/fancybox | https://github.com/fancyapps/fancybox             |
tiny-slider        | yarn add tiny-slider         | https://github.com/ganlanyuan/tiny-slider         |
vanilla-lazyload   | yarn add vanilla-lazyload    | https://github.com/verlok/vanilla-lazyload        |
body-scroll-lock   | yarn add body-scroll-lock    | https://github.com/willmcpo/body-scroll-lock      |
object-fit-images  | yarn add object-fit-images   | https://github.com/fregante/object-fit-images     |

