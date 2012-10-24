<?php
/*
Plugin Name: Social Count Plus
Plugin URI: http://www.claudiosmweb.com/
Description: Exiba a contagem de seguidores no Twitter, fãs do Facebook, total de posts e comentários.
Author: Claudio Sanches
Version: 1.3
Author URI: http://www.claudiosmweb.com/
*/

// Criar menu para o plugin no WP
function add_scp_menu() {
    add_options_page('Social Count Plus', 'Social Count Plus', 'manage_options', 'social-count-plus', 'admin_scp');
}
add_action('admin_menu', 'add_scp_menu');
// Adicionar opcoes no DB
function set_scp_options() {
    add_option('scp_feed','');
    add_option('scp_twitter','');
    add_option('scp_facebook','');
    add_option('scp_show_feed','true');
    add_option('scp_show_twitter','true');
    add_option('scp_show_facebook','true');
    add_option('scp_show_posts','true');
    add_option('scp_show_comment','true');
    add_option('scp_layout','default');
    add_option('scp_feed_cache','0');
    add_option('scp_twitter_cache','0');
    add_option('scp_facebook_cache','0');
}
// Deleta opcoes quando o plugin &eacute; desinstalado
function unset_scp_options() {
    delete_option('scp_feed');
    delete_option('scp_twitter');
    delete_option('scp_facebook');
    delete_option('scp_show_feed');
    delete_option('scp_show_twitter');
    delete_option('scp_show_facebook');
    delete_option('scp_show_posts');
    delete_option('scp_show_comment');
    delete_option('scp_layout');
    delete_option('scp_feed_cache');
    delete_option('scp_twitter_cache');
    delete_option('scp_facebook_cache');
    delete_transient('fan_count');
    delete_transient('follower_count');
    delete_transient('feed_count');
    delete_transient('posts_count');
    delete_transient('comments_count');
}
// instrucoes ao instalar ou desistalar o plugin
register_activation_hook(__FILE__,'set_scp_options');
register_deactivation_hook(__FILE__,'unset_scp_options');
// Pagina de opcoes
function admin_scp() {
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br /></div>
        <?php
        $reset_count = isset($_POST['reset']);
        if($reset_count == 'limpar') {
            delete_transient('fan_count');
            delete_transient('follower_count');
            //delete_transient('feed_count');
            delete_transient('posts_count');
            delete_transient('comments_count');
            ?><div id="message" class="updated fade">
            <p><?php _e('O cache foi deletado com sucesso!'); ?></p>
            </div><?php
        }
        if(!empty($_POST) && check_admin_referer('scp_nonce_action', 'scp_nonce_field') && !$reset_count == 'limpar') {
            update_scp_options();
        }
        print_scp_form();
        ?>
    </div>
    <?php
}
// Validar op&ccedil;&otilde;es
function update_scp_options() {
    $correto = false;
    // Feedburner user
    if (isset($_REQUEST['scp_feed'])) {
        update_option('scp_feed', $_REQUEST['scp_feed']);
        $correto = true;
    }
    // Twitter user
    if (isset($_REQUEST['scp_twitter'])) {
        update_option('scp_twitter', $_REQUEST['scp_twitter']);
        $correto = true;
    }
    // Facebook user
    if (isset($_REQUEST['scp_facebook'])) {
        update_option('scp_facebook', $_REQUEST['scp_facebook']);
        $correto = true;
    }
    // Show Feedburner
    if (isset($_REQUEST['scp_show_feed'])) {
        update_option('scp_show_feed', $_REQUEST['scp_show_feed']);
        $correto = true;
    }
    // Show Twitter
    if (isset($_REQUEST['scp_show_twitter'])) {
        update_option('scp_show_twitter', $_REQUEST['scp_show_twitter']);
        $correto = true;
    }
    // Show Facebook
    if (isset($_REQUEST['scp_show_facebook'])) {
        update_option('scp_show_facebook', $_REQUEST['scp_show_facebook']);
        $correto = true;
    }
    // Show Posts
    if (isset($_REQUEST['scp_show_posts'])) {
        update_option('scp_show_posts', $_REQUEST['scp_show_posts']);
        $correto = true;
    }
    // Show Comments
    if (isset($_REQUEST['scp_show_comment'])) {
        update_option('scp_show_comment', $_REQUEST['scp_show_comment']);
        $correto = true;
    }
    // Layout models
    if (isset($_REQUEST['scp_layout'])) {
        update_option('scp_layout', $_REQUEST['scp_layout']);
        $correto = true;
    }
    if ($correto) {
        ?><div id="message" class="updated fade">
        <p><?php _e('Op&ccedil;&otilde;es salvas.'); ?></p>
        </div><?php
        delete_transient('fan_count');
        delete_transient('follower_count');
        //delete_transient('feed_count');
        delete_transient('posts_count');
        delete_transient('comments_count');
    }
    else {
        ?><div id="message" class="error fade">
        <p><?php _e('Erro ao salvar op&ccedil;&otilde;es!'); ?></p>
        </div><?php
    }
}
// Limpa cache quando publica um novo artigo
function scp_clear_cache(){
    delete_transient('fan_count');
    delete_transient('follower_count');
    //delete_transient('feed_count');
    delete_transient('posts_count');
    delete_transient('comments_count');
}
add_action('publish_post', 'scp_clear_cache');
// Formulario com as opcoes
function print_scp_form() {
    $default_feed = get_option('scp_feed');
    $default_twitter = get_option('scp_twitter');
    $default_facebook = get_option('scp_facebook');
    $default_show_feed = get_option('scp_show_feed');
    $default_show_twitter = get_option('scp_show_twitter');
    $default_show_facebook = get_option('scp_show_facebook');
    $default_show_posts = get_option('scp_show_posts');
    $default_show_comment = get_option('scp_show_comment');
    $default_layout = get_option('scp_layout');
    $scp_plugin_dir = get_bloginfo('wpurl') . '/wp-content/plugins/social-count-plus';
    $scp_blog_dir = get_bloginfo('wpurl');
    if(!isset($_GET['pag'])) { ?>
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab nav-tab-active" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus"><?php _e('Social Count Plus'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=config"><?php _e('Configura&ccedil;&otilde;es'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=design"><?php _e('Design'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=shortcode"><?php _e('Shortcodes e Fun&ccedil;&otilde;es'); ?></a>
    </h2>
    <h3 style="margin:20px 0 -5px;"><?php _e('Introdu&ccedil;&atilde;o'); ?></h3>
    <p><?php _e('Bem vindo ao plugin Social Count Plus.'); ?></p>
    <p><?php _e('Com o Social Count Plus &eacute; poss&iacute;vel realizar a contagem de assinantes de feed (FeedBurner), seguidores do Twitter, f&atilde;s da sua p&aacute;gina no Facebook, posts publicados e coment&aacute;rios.'); ?></p>
    <p><?php _e('Sendo poss&iacute;vel mostrar seus resultados atrav&eacute;s de um simples Widget em sua sidebar ou atrav&eacute;s de shortcodes (ou fun&ccedil;&otilde;es em PHP caso seu tema n&atilde;o possua sidebar din&acirc;mica).'); ?></p>
    <p><?php _e('Todos os dados s&atilde;o guardados em cache que ficam salvos por 24 horas, desta forma impede que seu blog fique lento por causa do Social Count Plus.<br />Este cache &eacute; atualizado toda vez que &eacute; publicado um novo post ou pode ser limpo manualmente atrav&eacute;s do seguinte bot&atilde;o:'); ?></p>
    <form action="" method="post">
        <p>
            <strong style="padding:0 20px 0 0;">Limpar cache do contador:</strong>
            <?php wp_nonce_field('scp_nonce_action', 'scp_nonce_field'); ?>
            <input type="submit" class="button-primary" name="reset" value="Limpar" />
        </p>
    </form>
    <h3 style="margin:20px 0 -5px;"><?php _e('Instru&ccedil;&otilde;es de uso'); ?></h3>
    <p><?php _e('Antes de exibir o contador em seu site/blog é necessário seguir os seguintes passos:'); ?></p>
    <ul style="list-style:square;padding:0 0 0 30px;">
        <li><?php _e('Utilize o menu &quot;<a href="options-general.php?page=social-count-plus&pag=config">Configura&ccedil;&otilde;es</a>&quot; para inserir nome de usu&aacute;rio do Twitter, ID de sua p&aacute;gina no Facebook e controlar a exibi&ccedil;&atilde;o de todos os contadores que aparecem no Widget.'); ?></li>
        <li><?php _e('Em <a href="options-general.php?page=social-count-plus&pag=design">Design</a> selecione o modelo de widget que deseja exibir.'); ?></li>
        <li><?php _e('Encontre o widget &quot;Social Count Plus&quot; em: Apar&ecirc;ncia &gt; <a href="widgets.php">Widgets</a>. Agora basta arrasta-lo para sua sidebar!'); ?></li>
    </ul>
    <p style="padding:0 0 10px;"><?php _e('&Eacute; poss&iacute;vel ainda utilizar <a href="options-general.php?page=social-count-plus&pag=shortcode">Shortcodes e fun&ccedil;&otilde;es</a> em PHP para criar seus pr&oacute;prios modelos de contador ou exibir o qualquer um dos resultados dentro de posts e p&aacute;ginas.'); ?></p>
<?php
}
if(isset($_GET['pag']) && $_GET['pag'] == 'config') {
?>
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus"><?php _e('Social Count Plus'); ?></a>
        <a class="nav-tab nav-tab-active" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=config"><?php _e('Configura&ccedil;&otilde;es'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=design"><?php _e('Design'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=shortcode"><?php _e('Shortcodes e Fun&ccedil;&otilde;es'); ?></a>
    </h2>
    <form action="" method="post">
        <!-- <h3 style="margin:20px 0 -5px;"><?php _e('FeedBurner'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_feed"><?php _e('ID do FeedBurner'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_feed" id="scp_feed" value="<?php echo strip_tags($default_feed); ?>" />
                    <br /><span class="description"><?php _e('&Eacute; poss&iacute;vel encontrar esta informa&ccedil;&atilde;o no final do link de seu FeedBurner:<br />Exemplo: http://feeds.feedburner.com/<strong>ferramentasblog</strong><br/ >Para o perfeito funcionamento do contador &eacute; necess&aacute;rio ativar o &quot;Awareness API&quot; do FeedBurner<br />Ative esta ferramenta logando em <a href="http://feedburner.google.com/" target="_blank">http://feedburner.google.com/</a>, depois de logado selecione seu feed e clique na aba &quot;Publicize&quot;,<br />finalmente selecione o menu &quot;Awareness API&quot; e ative!'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_show_feed_yes"><?php _e('Exibir contador do FeedBurner'); ?></label></th>
                <td>
                    <label><input type="radio" id="scp_show_feed_yes" name="scp_show_feed" value="true" <?php if ($default_show_feed == "true") { _e('checked="checked"'); } ?> /> <?php _e('Sim'); ?></label>
                    <label><input style="margin:0 0 0 10px" type="radio" id="scp_show_feed_no" name="scp_show_feed" value="false" <?php if ($default_show_feed == "false") { _e('checked="checked"'); } ?>/> <?php _e('N&atilde;o'); ?></label>
                </td>
            </tr>
        </table> -->
        <h3 style="margin:20px 0 -5px;"><?php _e('Twitter'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_twitter"><?php _e('Username do Twitter'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_twitter" id="scp_twitter" value="<?php echo strip_tags($default_twitter); ?>" />
                    <br /><span class="description"><?php _e('Insira seu nome de usu&aacute;rio do Twitter<br/ >Exemplo: http://twitter.com/<strong>ferramentasblog</strong>'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_show_twitter_yes"><?php _e('Exibir contador do Twitter'); ?></label></th>
                <td>
                    <label><input type="radio" id="scp_show_twitter_yes" name="scp_show_twitter" value="true" <?php if ($default_show_twitter == "true") { _e('checked="checked"'); } ?> /> <?php _e('Sim'); ?></label>
                    <label><input style="margin:0 0 0 10px" type="radio" id="scp_show_twitter_no" name="scp_show_twitter" value="false" <?php if ($default_show_twitter == "false") { _e('checked="checked"'); } ?>/> <?php _e('N&atilde;o'); ?></label>
                </td>
            </tr>
        </table>
        <h3 style="margin:20px 0 -5px;"><?php _e('Facebook'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_facebook"><?php _e('ID do Facebook'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_facebook" id="scp_facebook" value="<?php echo strip_tags($default_facebook); ?>" />
                    <br /><span class="description"><?php _e('ID num&eacute;rico da sua p&aacute;gina no Facebook<br />Voc&ecirc; pode encontra-lo clicando em &quot;Editar p&aacute;gina&quot; (no Facebook), onde voc&ecirc; ir&aacute; encontrar uma URL similar a esta:<br /> https://www.facebook.com/pages/edit/?id=<strong>162354720442454</strong>'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_show_facebook_yes"><?php _e('Exibir contador do Facebook'); ?></label></th>
                <td>
                    <label><input type="radio" id="scp_show_facebook_yes" name="scp_show_facebook" value="true" <?php if ($default_show_facebook == "true") { _e('checked="checked"'); } ?> /> <?php _e('Sim'); ?></label>
                    <label><input style="margin:0 0 0 10px" type="radio" id="scp_show_facebook_no" name="scp_show_facebook" value="false" <?php if ($default_show_facebook == "false") { _e('checked="checked"'); } ?>/> <?php _e('N&atilde;o'); ?></label>
                </td>
            </tr>
        </table>
        <h3 style="margin:20px 0 -5px;"><?php _e('Posts'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_show_posts_yes"><?php _e('Exibir contador do Posts'); ?></label></th>
                <td>
                    <label><input type="radio" id="scp_show_posts_yes" name="scp_show_posts" value="true" <?php if ($default_show_posts == "true") { _e('checked="checked"'); } ?> /> <?php _e('Sim'); ?></label>
                    <label><input style="margin:0 0 0 10px" type="radio" id="scp_show_posts_no" name="scp_show_posts" value="false" <?php if ($default_show_posts == "false") { _e('checked="checked"'); } ?>/> <?php _e('N&atilde;o'); ?></label>
                </td>
            </tr>
        </table>
        <h3 style="margin:20px 0 -5px;"><?php _e('Coment&aacute;rios'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_show_comment_yes"><?php _e('Exibir contador do Coment&aacute;rios'); ?></label></th>
                <td>
                    <label><input type="radio" id="scp_show_comment_yes" name="scp_show_comment" value="true" <?php if ($default_show_comment == "true") { _e('checked="checked"'); } ?> /> <?php _e('Sim'); ?></label>
                    <label><input style="margin:0 0 0 10px" type="radio" id="scp_show_comment_no" name="scp_show_comment" value="false" <?php if ($default_show_comment == "false") { _e('checked="checked"'); } ?>/> <?php _e('N&atilde;o'); ?></label>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php wp_nonce_field('scp_nonce_action', 'scp_nonce_field'); ?>
            <input type="submit" class="button-primary" name="submit" value="Salvar" />
        </p>
    </form>
<?php
}
if(isset($_GET['pag']) && $_GET['pag'] == 'design') {
    ?>
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus"><?php _e('Social Count Plus'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=config"><?php _e('Configura&ccedil;&otilde;es'); ?></a>
        <a class="nav-tab nav-tab-active" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=design"><?php _e('Design'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=shortcode"><?php _e('Shortcodes e Fun&ccedil;&otilde;es'); ?></a>
    </h2>
    <form action="" method="post">
        <h3 style="margin:20px 0 -5px;"><?php _e('Modelos de layout'); ?></h3>
        <table class="form-table">
            <tr>
                <td colspan="2"><p><?php _e('Seleciona uma das op&ccedil;&otilde;es de layout para o widget.<br /><br />Em breve teremos outros formatos de layouts!'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_layout_default"><?php _e('Op&ccedil;&otilde;es de layout'); ?></label></th>
                <td>
                    <label style="margin:0;padding:0;display:block"><input type="radio" id="scp_layout_default" name="scp_layout" value="default" <?php if ($default_layout == "default") { _e('checked="checked"'); } ?> /><img style="display:block;margin:-20px 0 30px 20px;" src="<?php echo $scp_plugin_dir; ?>/demo/design-default.png" alt="design default" /></label>
                    <label style="margin:0;padding:0;display:block"><input type="radio" id="scp_layout_circle" name="scp_layout" value="circle" <?php if ($default_layout == "circle") { _e('checked="checked"'); } ?>/><img style="display:block;margin:-15px 0 30px 20px;" src="<?php echo $scp_plugin_dir; ?>/demo/design-circle.png" alt="design circle" /></label>
                    <label style="margin:0;padding:0;display:block"><input type="radio" id="scp_layout_vertical-square" name="scp_layout" value="vertical-square" <?php if ($default_layout == "vertical-square") { _e('checked="checked"'); } ?>/><img style="display:block;margin:-18px 0 30px 20px;" src="<?php echo $scp_plugin_dir; ?>/demo/design-vertical-square.png" alt="design vertical square" /></label>
                    <label style="margin:0;padding:0;display:block"><input type="radio" id="scp_layout_vertical-square" name="scp_layout" value="vertical-circle" <?php if ($default_layout == "vertical-circle") { _e('checked="checked"'); } ?>/><img style="display:block;margin:-15px 0 30px 20px;" src="<?php echo $scp_plugin_dir; ?>/demo/design-vertical-circle.png" alt="design vertical circle" /></label>
                    <label style="margin:0;padding:0;display:block"><input type="radio" id="scp_layout_none" name="scp_layout" value="none" <?php if ($default_layout == "none") { _e('checked="checked"'); } ?>/>Desligar CSS</label>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php wp_nonce_field('scp_nonce_action', 'scp_nonce_field'); ?>
            <input type="submit" class="button-primary" name="submit" value="Salvar" />
        </p>
    </form>
<?php
}
if(isset($_GET['pag']) && $_GET['pag'] == 'shortcode') {
    ?>
    <h2 class="nav-tab-wrapper">
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus"><?php _e('Social Count Plus'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=config"><?php _e('Configura&ccedil;&otilde;es'); ?></a>
        <a class="nav-tab" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=design"><?php _e('Design'); ?></a>
        <a class="nav-tab nav-tab-active" href="<?php echo $scp_blog_dir; ?>/wp-admin/options-general.php?page=social-count-plus&pag=shortcode"><?php _e('Shortcodes e Fun&ccedil;&otilde;es'); ?></a>
    </h2>
    <form action="" method="post">
        <p><?php _e('Utilize esta biblioteca de Shortcodes e Fun&ccedil;&otilde;es para gerar seu pr&oacute;prio modelo de layout ou exibir dados espec&iacute;ficos dos contadores.'); ?></p>
        <h3 style="margin:20px 0 -5px;"><?php _e('Shortcodes'); ?></h3>
        <p><?php _e('Com os Shortcodes a baixo &eacute; poss&iacute;vel exibir o contador de cada op&ccedil;&atilde;o dentro posts ou p&aacute;ginas:'); ?></p>
        <table class="form-table">
            <!-- <tr>
                <th scope="row"><?php _e('Assinantes de feed (FeedBurner)'); ?></th>
                <td><p><?php _e('<code>[scp code=&quot;feed&quot;]</code>'); ?></p></td>
            </tr> -->
            <tr>
                <th scope="row"><?php _e('Seguidores no Twitter'); ?></th>
                <td><p><?php _e('<code>[scp code=&quot;twitter&quot;]</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('F&atilde;s do Facebook'); ?></th>
                <td><p><?php _e('<code>[scp code=&quot;facebook&quot;]</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Total de posts'); ?></th>
                <td><p><?php _e('<code>[scp code=&quot;posts&quot;]</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Total de coment&aacute;rios'); ?></th>
                <td><p><?php _e('<code>[scp code=&quot;comments&quot;]</code>'); ?></p></td>
            </tr>
        </table>
        <h3 style="margin:20px 0 -5px;"><?php _e('Fun&ccedil;&otilde;es'); ?></h3>
        <p><?php _e('A partir destas funções é possível criar novos layouts para o contador diretamente do código fonte de seu tema:'); ?></p>
        <table class="form-table">
            <!-- <tr>
                <th scope="row"><?php _e('Assinantes de feed (FeedBurner)'); ?></th>
                <td><p><?php _e('<code>&lt;?php echo get_scp_feed(); ?&gt;</code>'); ?></p></td>
            </tr> -->
            <tr>
                <th scope="row"><?php _e('Seguidores no Twitter'); ?></th>
                <td><p><?php _e('<code>&lt;?php echo get_scp_twitter(); ?&gt;</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('F&atilde;s do Facebook'); ?></th>
                <td><p><?php _e('<code>&lt;?php echo get_scp_facebook(); ?&gt;</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Total de posts'); ?></th>
                <td><p><?php _e('<code>&lt;?php echo get_scp_posts(); ?&gt;</code>'); ?></p></td>
            </tr>
            <tr>
                <th scope="row"><?php _e('Total de coment&aacute;rios'); ?></th>
                <td><p><?php _e('<code>&lt;?php echo get_scp_comments(); ?&gt;</code>'); ?></p></td>
            </tr>
        </table>
        <h3 style="margin:20px 0 -5px;"><?php _e('Widget via Fun&ccedil;&atilde;o'); ?></h3>
        <p><?php _e('Use esta fun&ccedil;&atilde;o caso seu tema n&atilde;o tenha sidebar din&acirc;mica. Desta forma voc&ecirc; chama automaticamente o widget &quot;Social Count Plus&quot; direto para o tema:'); ?></p>
        <table class="form-table">
            <tr>
                <th scope="row"><?php _e('Widget Social Count Plus'); ?></th>
                <td><p><?php _e('<code>&lt;?php get_scp_widget(); ?&gt;</code>'); ?></p></td>
            </tr>
        </table>
    </form>
<?php
}
?>
    <p style="margin:20px 0 0;">
        <a href="http://www.fbloghost.com/plano-wp-host/" target="_blank" title="FBlogHost - Hospedagem profissional para Worpdress">
            <img style="border:none;" src="<?php echo $scp_plugin_dir; ?>/fbloghost.jpg" alt="FBlogHost - Hospedagem profissional para Worpdress" />
        </a>
    </p>
<?php
}
// JS e CSS do plugin no head
function scp_css_head() {
    $scp_layout = get_option('scp_layout');
    $scp_plugin_dir = get_bloginfo('wpurl') . '/wp-content/plugins/social-count-plus';
    $layout_default = "<style type=\"text/css\">
    ul.scp-wrap {list-style:none !important;margin:0;padding:0;}
    ul.scp-wrap li .scp-img {line-height:16px;margin:0 auto;padding:0;width:32px;height:32px;display:block;background-color:transparent;background-image:url($scp_plugin_dir/images/sprite-default.png);background-repeat:no-repeat;transition:all 0.4s ease;-webkit-transition:all 0.4s ease;-o-transition:all 0.4s ease;-moz-transition:all 0.4s ease;opacity:1;}
    ul.scp-wrap li .scp-img:hover {opacity:0.7;}
    ul.scp-wrap li {margin:0;padding:0 0 20px;width:60px;text-align:center;float:left;background:none;clear:none !important}
    ul.scp-wrap li.scp-feed .scp-img {background-position:0 0;}
    ul.scp-wrap li.scp-twitter .scp-img {background-position:-32px 0;}
    ul.scp-wrap li.scp-facebook .scp-img {background-position:-64px 0;}
    ul.scp-wrap li.scp-posts .scp-img {background-position:-96px 0;}
    ul.scp-wrap li.scp-comments .scp-img {background-position:-128px 0;}
    ul.scp-wrap li .scp-count {display:block;color:#333;font-weight:bold;font-size:14px;padding:0;margin:5px 0 0;line-height:16px;}
    ul.scp-wrap li .scp-label {display:block;text-transform:capitalize;color:#333;font-weight:normal;font-size:9px;padding:0;margin:0;line-height:16px;}
    .clear {clear:both;}
</style>\n";
    $layout_circle = "<style type=\"text/css\">
    ul.scp-wrap {list-style:none !important;margin:0;padding:0;}
    ul.scp-wrap li .scp-img {line-height:16px;margin:0 auto;padding:0;width:36px;height:37px;display:block;background-color:transparent;background-image:url($scp_plugin_dir/images/sprite-circle.png);background-repeat:no-repeat;transition:all 0.4s ease;-webkit-transition:all 0.4s ease;-o-transition:all 0.4s ease;-moz-transition:all 0.4s ease;opacity:1;}
    ul.scp-wrap li .scp-img:hover {opacity:0.7;}
    ul.scp-wrap li {margin:0;padding:0 0 20px;width:60px;text-align:center;float:left;background:none;clear:none !important}
    ul.scp-wrap li.scp-feed .scp-img {background-position:0 0;}
    ul.scp-wrap li.scp-twitter .scp-img {background-position:-36px 0;}
    ul.scp-wrap li.scp-facebook .scp-img {background-position:-72px 0;}
    ul.scp-wrap li.scp-posts .scp-img {background-position:-108px 0;}
    ul.scp-wrap li.scp-comments .scp-img {background-position:-144px 0;}
    ul.scp-wrap li .scp-count {display:block;color:#333;font-weight:bold;font-size:14px;padding:0;margin:5px 0 0;line-height:16px;}
    ul.scp-wrap li .scp-label {display:block;text-transform:capitalize;color:#333;font-weight:normal;font-size:9px;padding:0;margin:0;line-height:16px;}
    .clear {clear:both;}
</style>\n";
    $layout_vertical_square = "<style type=\"text/css\">
    ul.scp-wrap {list-style:none !important;margin:0;padding:0;}
    ul.scp-wrap li .scp-img {line-height:16px;float:left;margin:0 10px 0 0;padding:0;width:32px;height:32px;display:block;background-color:transparent;background-image:url($scp_plugin_dir/images/sprite-default.png);background-repeat:no-repeat;transition:all 0.4s ease;-webkit-transition:all 0.4s ease;-o-transition:all 0.4s ease;-moz-transition:all 0.4s ease;opacity:1;}
    ul.scp-wrap li .scp-img:hover {opacity:0.7;}
    ul.scp-wrap li {margin:0;padding:0 0 20px;height:25px;display:block;background:none;clear:none !important}
    ul.scp-wrap li.scp-feed .scp-img {background-position:0 0;}
    ul.scp-wrap li.scp-twitter .scp-img {background-position:-32px 0;}
    ul.scp-wrap li.scp-facebook .scp-img {background-position:-64px 0;}
    ul.scp-wrap li.scp-posts .scp-img {background-position:-96px 0;}
    ul.scp-wrap li.scp-comments .scp-img {background-position:-128px 0;}
    ul.scp-wrap li .scp-count {display:block;color:#333;font-weight:bold;font-size:14px;padding:0;margin:0;line-height:16px;}
    ul.scp-wrap li .scp-label {display:block;text-transform:capitalize;color:#333;font-weight:normal;font-size:9px;padding:0;margin:0;line-height:16px;}
    .clear {clear:both;}
</style>\n";
    $layout_vertical_circle = "<style type=\"text/css\">
    ul.scp-wrap {list-style:none !important;margin:0;padding:0;}
    ul.scp-wrap li .scp-img {line-height:16px;float:left;margin:0 10px 0 0;padding:0;width:36px;height:37px;display:block;background-color:transparent;background-image:url($scp_plugin_dir/images/sprite-circle.png);background-repeat:no-repeat;transition:all 0.4s ease;-webkit-transition:all 0.4s ease;-o-transition:all 0.4s ease;-moz-transition:all 0.4s ease;opacity:1;}
    ul.scp-wrap li .scp-img:hover {opacity:0.7;}
    ul.scp-wrap li {margin:0;padding:0 0 20px;height:25px;display:block;background:none;clear:none !important}
    ul.scp-wrap li.scp-feed .scp-img {background-position:0 0;}
    ul.scp-wrap li.scp-twitter .scp-img {background-position:-36px 0;}
    ul.scp-wrap li.scp-facebook .scp-img {background-position:-72px 0;}
    ul.scp-wrap li.scp-posts .scp-img {background-position:-108px 0;}
    ul.scp-wrap li.scp-comments .scp-img {background-position:-144px 0;}
    ul.scp-wrap li .scp-count {display:block;color:#333;font-weight:bold;font-size:14px;padding:3px 0 0;margin:0;line-height:16px;}
    ul.scp-wrap li .scp-label {display:block;text-transform:capitalize;color:#333;font-weight:normal;font-size:9px;padding:0;margin:0;line-height:16px;}
    .clear {clear:both;}
</style>\n";
    $layout_none = null;
    switch ($scp_layout) {
        case "circle":
            $scp_layout_final = $layout_circle;
            break;
        case "vertical-square":
            $scp_layout_final = $layout_vertical_square;
            break;
        case "vertical-circle":
            $scp_layout_final = $layout_vertical_circle;
            break;
        case "none":
            $scp_layout_final = $layout_none;
            break;
        default :
            $scp_layout_final = $layout_default;
            break;
    }
    echo $scp_layout_final;
}
add_filter('wp_head', 'scp_css_head');
// Feedburner Count
function get_scp_feed(){
    /*$feed_user = get_option('scp_feed');
    $count = get_transient('feed_count');
    if($count != 0) {
        update_option('scp_feed_cache', $count);
    }
    if ($count != false) return $count;
    $count = 0;
    $data = wp_remote_get("http://feedburner.google.com/api/awareness/1.0/GetFeedData?uri=http://feeds.feedburner.com/$feed_user");
    if (is_wp_error($data)) {
        return '0';
    }
    else {
        $body = wp_remote_retrieve_body($data);
        $xml = new SimpleXMLElement($body);
        $status = $xml->attributes();
        if ($status == 'ok') {
            $count = (string) $xml->feed->entry->attributes()->circulation;
        } else {
            $count = '0';
        }
    }
    set_transient('feed_count', $count, 60*60*24); // 24 horas de cache
    if(empty($count)) {
        $count_old = get_option('scp_feed_cache');
        $count = $count_old;
    }
    else {
        $count;
    }*/
    $count = 0;
    return $count;
}
// Twitter Count
function get_scp_twitter(){
    $tw_user = get_option('scp_twitter');
    $count = get_transient('follower_count');
    if($count != 0) {
        update_option('scp_twitter_cache', $count);
    }
    if ($count !== false) return $count;
    $count = 0;
    $data = wp_remote_get("http://api.twitter.com/1/users/show.json?screen_name=$tw_user");
    if (!is_wp_error($data)) {
        $value = json_decode($data['body'],true);
        if(isset($value['followers_count'])) {
            $count = $value['followers_count'];
        }
    }
    if($count == '') {
        $count_old = get_option('scp_twitter_cache');
        $count = $count_old;
    }
    set_transient('follower_count', $count, 60*60*24); // 24 horas de cache
    return $count;
}
// Facebook Count
function get_scp_facebook(){
    $fb_id = get_option('scp_facebook');
    $count = get_transient('fan_count');
    if($count != 0) {
        update_option('scp_facebook_cache', $count);
    }
    if ($count !== false) return $count;
    $count = 0;
    $data = wp_remote_get("http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=$fb_id");
    if (!is_wp_error($data)) {
        $xml = new SimpleXmlElement($data['body'], LIBXML_NOCDATA);
        $count = (string) $xml->page->fan_count;
    } else {
        $count = 0;
    }
    if($count == '') {
        $count_old = get_option('scp_facebook_cache');
        $count = $count_old;
    }
    set_transient('fan_count', $count, 60*60*24); // 24 horas de cache
    return $count;
}
// Posts Count
function get_scp_posts(){
    $count = get_transient('posts_count');
    if ($count != false) return $count;
    $count = 0;
    $count_posts = wp_count_posts();
    $published_posts = $count_posts->publish;
    if (is_wp_error($published_posts)) {
        return '0';
    }
    else {
        $count = $published_posts;
    }
    set_transient('posts_count', $count, 60*60*24); // 24 horas de cache
    return $count;
}
// Comments Count
function get_scp_comments(){
    $count = get_transient('comments_count');
    if ($count != false) return $count;
    $count = 0;
    $comments_count = wp_count_comments();
    $approved_comments = $comments_count->approved;
    if (is_wp_error($approved_comments)) {
        return '0';
    }
    else {
        $count = $approved_comments;
    }
    set_transient('comments', $count, 60*60*24); // 24 hour cache
    return $count;
}
// WP Widget
function get_scp_widget() {
    //$scp_url_feed = 'http://feeds.feedburner.com/' . get_option('scp_feed');
    $scp_url_twitter = 'http://twitter.com/' . get_option('scp_twitter');
    $scp_url_facebook = 'http://www.facebook.com/' . get_option('scp_facebook');
    //$scp_show_feed = get_option('scp_show_feed');
    $scp_show_twitter = get_option('scp_show_twitter');
    $scp_show_facebook = get_option('scp_show_facebook');
    $scp_show_posts = get_option('scp_show_posts');
    $scp_show_comment = get_option('scp_show_comment');
    $li_count = 0;
?>
<ul class="scp-wrap">
<?php if($scp_show_twitter == 'false') { echo ''; } else { ?><li class="scp-twitter scp-box-num-<?php $li_count++; echo $li_count; ?>"><a href="<?php echo $scp_url_twitter; ?>" target="_blank"><span class="scp-img"></span></a><span class="scp-count"><?php echo get_scp_twitter(); ?></span><span class="scp-label"><?php _e('seguidores'); ?></span></li><?php }
if($scp_show_facebook == 'false') { echo ''; } else { ?><li class="scp-facebook scp-box-num-<?php $li_count++; echo $li_count; ?>"><a href="<?php echo $scp_url_facebook; ?>" target="_blank"><span class="scp-img"></span></a><span class="scp-count"><?php echo get_scp_facebook(); ?></span><span class="scp-label"><?php _e('fãs'); ?></span></li><?php }
if($scp_show_posts == 'false') { echo ''; } else { ?><li class="scp-posts scp-box-num-<?php $li_count++; echo $li_count; ?>"><span class="scp-img"></span><span class="scp-count"><?php echo get_scp_posts(); ?></span><span class="scp-label"><?php _e('artigos'); ?></span></li><?php }
if($scp_show_comment == 'false') { echo ''; } else { ?><li class="scp-comments scp-box-num-<?php $li_count++; echo $li_count; ?>"><span class="scp-img"></span><span class="scp-count"><?php echo get_scp_comments(); ?></span><span class="scp-label"><?php _e('comentários'); ?></span></li><?php }
?>
</ul>
<div class="clear"></div>
<?php
}
// Register Widget
class SocialCountPlus extends WP_Widget {
    function SocialCountPlus() {
        $widget_ops = array('social_count_plus' => 'SocialCountPlus', 'description' => 'Exibir contador' );
        $this->WP_Widget('SocialCountPlus', 'Social Count Plus', $widget_ops);
    }
    function form($instance) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
        $title = $instance['title'];
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        return $instance;
    }
    function widget($args, $instance) {
        extract($args, EXTR_SKIP);
        echo $before_widget;
        $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
        if (!empty($title)) {
            echo $before_title . $title . $after_title;
        }
        // Display widget
        echo get_scp_widget();
        echo $after_widget;
    }
}
add_action('widgets_init', create_function('', 'return register_widget("SocialCountPlus");'));
// Shortcodes
function scp_shortcodes($atts) {
    //$scp_feed = get_scp_feed();
    $scp_twitter = get_scp_twitter();
    $scp_facebook = get_scp_facebook();
    $scp_posts = get_scp_posts();
    $scp_comments = get_scp_comments();
    extract( shortcode_atts( array(
        'code' => 'feed'
    ), $atts ) );
    switch ($code) {
        case "feed" :
            //$scp_code = $scp_feed;
            break;
        case "twitter" :
            $scp_code = $scp_twitter;
            break;
        case "facebook" :
            $scp_code = $scp_facebook;
            break;
        case "posts" :
            $scp_code = $scp_posts;
            break;
        case "comments" :
            $scp_code = $scp_comments;
            break;
        default :
            //$scp_code = $scp_feed;
            break;
    }
    return $scp_code;
}
add_shortcode('scp', 'scp_shortcodes');
?>