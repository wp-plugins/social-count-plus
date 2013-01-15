# Social Count Plus #
**Contributors:** claudiosanches  
**Donate link:** http://claudiosmweb.com/doacoes/  
**Tags:** facebook, twitter, youtube, counter, widget, shortcode  
**Requires at least:** 3.4  
**Tested up to:** 3.5  
**Stable tag:** 2.0.1  
**License:** GPLv2 or later  
**License URI:** http://www.gnu.org/licenses/gpl-2.0.html  

Display the counting Twitter followers, Facebook fans, YouTube subscribers posts and comments.

## Description ##

The Social Count Plus performs counting Twitter followers, Facebook fans, YouTube subscribers, posts and comments.

You can view this information via a widget (with account options models icons) or Shortcodes (to be used in posts and pages) or by functions in PHP.

The results of the counters are cached and new values ​​are checked only once a day. This cache can be wiped when published a new post.

The cache avoids not only that your blog be seeking new results every time a page is loaded, but also prevents collapse of services from Twitter and Facebook, if one of these services does not respond , the counter displays the last count it was successful.

#### Shortcodes ####

* Twitter: `[scp code="twitter"]`
* Facebook: `[scp code="facebook"]`
* Posts: `[scp code="posts"]`
* Comments: `[scp code="comments"]`

#### Functions ####

* Twitter: `<?php echo get_scp_twitter(); ?>`
* Facebook: `<?php echo get_scp_facebook(); ?>`
* Posts: `<?php echo get_scp_posts(); ?>`
* Comments: `<?php echo get_scp_comments(); ?>`
* Widget: `<?php get_scp_widget(); ?>`

### Descrição em Português: ###

O Social Count Plus faz a contagem de seguidores no Twitter, fãs no Facebook, assinantes do YouTube, total de posts e comentários.

Você pode exibir estas contagens através de um widget, com Shortcodes ou por funções em PHP.

Os resultados dos contadores são armazenados em cache e os novos valores são verificados apenas uma vez por dia. Este cache pode ser limpo quando publicado um novo post.

O cache não só evita que o seu blog estar buscando novos resultados cada vez que uma página é carregada, mas também impede o colapso dos serviços do Twitter e Facebook, se um desses serviços não responder, o contador exibe a última contagem que foi bem sucedida.

#### Shortcodes ####

* Twitter: `[scp code="twitter"]`
* Facebook: `[scp code="facebook"]`
* Posts: `[scp code="posts"]`
* Comments: `[scp code="comments"]`

#### Funções ####

* Twitter: `<?php echo get_scp_twitter(); ?>`
* Facebook: `<?php echo get_scp_facebook(); ?>`
* Posts: `<?php echo get_scp_posts(); ?>`
* Comments: `<?php echo get_scp_comments(); ?>`
* Widget: `<?php get_scp_widget(); ?>`

## Installation ##

* Upload plugin files to your plugins folder, or install using WordPress built-in Add New Plugin installer;
* Activate the plugin;
* Navigate to Settings -> Social Count Plus and fill the plugin options.

### Instalação em Português: ###

* Envie arquivos do plugin para a pasta de plugins, ou instale usando o o instalador do WordPress em Plugins -> Adicionar Novo;
* Ative o plugin;
* Navegue até Configurações -> Social Count Plus e preencha as opções do plugin.

## Frequently Asked Questions ##

### What is the plugin license? ###

* This plugin is released under a GPL license.

### Why the counter Facebook does not leave the ground? ###

* Because you need to have a fan page with more than 15 people in it to run the Facebook API.

### FAQ em Português: ###

### Qual é a licença do plugin? ###

* Este plugin esta licenciado como GPL.

### Porque o contador do Facebook não sai do zero? ###

* Porque você precisa ter uma fã page com mais de 15 pessoas nela para funcionar a API do Facebook.

## Screenshots ##

### 1. Plugin settings. ###
![1. Plugin settings.](http://s.wordpress.org/extend/plugins/social-count-plus/screenshot-1.png)

### 2. Widget models. ###
![2. Widget models.](http://s.wordpress.org/extend/plugins/social-count-plus/screenshot-2.png)

### 3. Shortcodes and Functions API page. ###
![3. Shortcodes and Functions API page.](http://s.wordpress.org/extend/plugins/social-count-plus/screenshot-3.png)

### 4. Widget. ###
![4. Widget.](http://s.wordpress.org/extend/plugins/social-count-plus/screenshot-4.png)


## Changelog ##

### 2.0.1 14/01/2013 ###

* Fixed styles.
* Fixed YouTube widget icon.

### 2.0 14/01/2013 ###

* Source code reformulation.
* Added YouTube counter.
* Improved performance with fewer options in the database.
* Added Brazilian Portuguese and English languages.

### 1.3 ###

* Removed support for Feedburner since Google has disabled the [API](https://developers.google.com/feedburner/).

### 1.2 ###

* Free version.

### 1.1 ###

* Final configuration of the plugin.

### 1.0 ###

* Initial release.

## License ##

This file is part of Social Count Plus.
Social Count Plus is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Social Count Plus is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Author Bio Box. If not, see <http://www.gnu.org/licenses/>.
