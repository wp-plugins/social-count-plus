=== Social Count Plus ===
Contributors: claudiosanches
Tags: facebook, twitter, feedburner, counter, widget, shortcode
Requires at least: 3.2
Tested up to: 3.3.1
Stable tag: 1.2

Com o Social Count Plus &eacute; poss&iacute;vel realizar a contagem de assinantes de feed (FeedBurner), seguidores do Twitter, f&atilde;s no Facebook, posts publicados e coment&aacute;rios.
 
== Description ==

O Social Count Plus realiza a contagem de assinantes de feed (FeedBurner), seguidores do Twitter, f&atilde;s de sua p&aacute;gina no Facebook, total de posts e coment&aacute;rios.
&Eacute; poss&iacute;vel exibir estas informa&ccedil;&otilde;es atrav&eacute;s de um Widget  (conta com op&ccedil;&otilde;es de modelos de &iacute;cones) ou por Shortcodes (para serem usados em posts e p&aacute;ginas) ou ainda por fun&ccedil;&otilde;es em PHP.
Os resultados dos contadores s&atilde;o guardados em cache e novos valores s&atilde;o verificados apenas uma vez por dia.
Este cache pode ser limpo quando publicado um novo post ou diretamente do menu do plugin em Configura&ccedil;&otilde;es &gt; Social Count Plus.
O cache n&atilde;o evita apenas que seu blog fique procurando novos resultados toda vez que sua p&aacute;gina &eacute; carregada, como tamb&eacute;m previne queda dos servi&ccedil;os do FeedBurner, Twitter e Facebook, caso um destes servi&ccedil;os n&atilde;o responda, o contador exibe a &uacute;ltima contagem que foi realizada com sucesso.
 
== Installation ==
 
Instale Social Count Plus atrav&eacute;s do diret&oacute;rio de plugin WordPress.org, ou enviando os arquivos para o servidor e inserindo na pasta wp-content/plugins.
Depois de instalado basta logar em seu blog e ir em Configura&ccedil;&otilde;es > Social Count Plus, para configurar suas op&ccedil;&otilde;es.
Agora &eacute; poss&iacute;vel adicionar o Widget do Social Count Plus em seu blog.

&Eacute; poss&iacute;vel ainda usar um destes shortcodes para inserir a contagem dentro de posts e p&aacute;ginas:

Assinantes de feed (FeedBurner): `[scp code="feed"]`
Seguidores no Twitter: `[scp code="twitter"]`
F&atilde;s do Facebook: `[scp code="facebook"]`
Total de posts: `[scp code="posts"]`
Total de coment&aacute;rios: `[scp code="comments"]`

Ou fun&ccedil;&otilde;es em PHP para o seu tema:

Assinantes de feed (FeedBurner): `<?php echo get_scp_feed(); ?>`
Seguidores no Twitter: `<?php echo get_scp_twitter(); ?>`
F&atilde;s do Facebook: `<?php echo get_scp_facebook(); ?>`
Total de posts: `<?php echo get_scp_posts(); ?>`
Total de coment&aacute;rios: `<?php echo get_scp_comments(); ?>`
Widget Social Count Plus: `<?php get_scp_widget(); ?>`

== License ==
 
This file is part of Social Count Plus.
Social Count Plus is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published
by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
Social Count Plus is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
You should have received a copy of the GNU General Public License along with Social Count Plus. If not, see <http://www.gnu.org/licenses/>.
 
== Frequently Asked Questions ==
 
= Can I suggest a feature for the plugin? =
Of course, visit [Social Count Plus](http://www.claudiosmweb.com/)
 
== Changelog ==

= 1.2 =
* Versão livre

= 1.1 =
* Configura&ccedil;&atilde;o definitiva do plugin

= 1.0 =
* Cria&ccedil;&atilde;o de desenvolvimento do plugin

== Screenshots ==
1. Plugin em funcionamento.
2. Op&ccedil;&otilde;es do plugin