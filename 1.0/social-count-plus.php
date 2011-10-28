<?php
/*
Plugin Name: Social Count Plus
Plugin URI: http://www.claudiosmweb.com/
Description: Widget que mostrar total de assinantes de feed, f&atilde;s no facebook, seguidores no twitter, posts e coment&aacute;rios
Author: Claudio Sanches
Version: 1.0
Author URI: http://www.claudiosmweb.com/
*/

// Criar menu para o plugin no WP
function add_scp_menu() {
    add_options_page('Social Count Plus', 'Social Count Plus', 'manage_options', __FILE__, 'admin_scp');
}
add_action('admin_menu', 'add_scp_menu');
// Adicionar opcoes no DB
function set_scp_options() {
    add_option('scp_feed','');
    add_option('scp_twitter','');
    add_option('scp_facebook','');
}
// Deleta opcoes quando o plugin &eacute; desinstalado
function unset_scp_options() {
    delete_option('scp_feed');
    delete_option('scp_twitter');
    delete_option('scp_facebook');
}
// instrucoes ao instalar ou desistalar o plugin
register_activation_hook(__FILE__,'set_scp_options');
register_deactivation_hook(__FILE__,'unset_scp_options');
// Pagina de opcoes
function admin_scp() {
    ?>
    <div class="wrap">
        <div class="icon32" id="icon-options-general"><br /></div>
        <h2>Social Count Plus Op&ccedil;&otilde;es</h2>
        <?php 
        $reset_count = $_POST['reset'];
        if($reset_count == 'limpar') {
            delete_transient('fan_count');
            delete_transient('follower_count');
            delete_transient('feed_count');
            delete_transient('posts_count');
            delete_transient('comments_count');
            ?><div id="message" class="updated fade">
            <p><?php _e('O cache foi deletado com sucesso!'); ?></p>
            </div><?php
        }
        if($_REQUEST['submit']) {
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
    if ($_REQUEST['scp_feed']) {
        update_option('scp_feed', $_REQUEST['scp_feed']);
        $correto = true;
    }
    // Twitter user
    if ($_REQUEST['scp_twitter']) {
        update_option('scp_twitter', $_REQUEST['scp_twitter']);
        $correto = true;
    }
    // Facebook user
    if ($_REQUEST['scp_facebook']) {
        update_option('scp_facebook', $_REQUEST['scp_facebook']);
        $correto = true;
    }
    if ($correto) {
        ?><div id="message" class="updated fade">
        <p><?php _e('Op&ccedil;&otilde;es salvas.'); ?></p>
        </div> <?php
    }
    else {
        ?><div id="message" class="error fade">
        <p><?php _e('Erro ao salvar op&ccedil;&otilde;es!'); ?></p>
        </div><?php
    }
}

// Formulario com as opcoes
function print_scp_form() {
    $default_feed = get_option('scp_feed');
    $default_twitter = get_option('scp_twitter');
    $default_facebook = get_option('scp_facebook');
    $scp_plugin_dir = get_bloginfo('wpurl') . '/wp-content/plugins/author-bio-box/';
    ?>
    <form action="" method="post">
        <h3 style="margin: 20px 0 -5px;"><?php _e('Apar&ecirc;ncia'); ?></h3>
        <table class="form-table">
            <tr>
                <th scope="row"><label for="scp_feed"><?php _e('ID do FeedBurner'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_feed" id="scp_feed" value="<?php echo stripcslashes($default_feed); ?>" />
                    <br /><span class="description"><?php _e('Info'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_twitter"><?php _e('ID do Twitter'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_twitter" id="scp_twitter" value="<?php echo stripcslashes($default_twitter); ?>" />
                    <br /><span class="description"><?php _e('Info'); ?></span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="scp_facebook"><?php _e('ID do Facebook'); ?></label></th>
                <td>
                    <input type="text" class="regular-text" name="scp_facebook" id="scp_facebook" value="<?php echo stripcslashes($default_facebook); ?>" />
                    <br /><span class="description"><?php _e('Info'); ?></span>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input type="submit" class="button-primary" name="submit" value="salvar" />
            <p>Limpar cache do contador:<br />
                <input type="submit" class="button-primary" name="reset" value="limpar" />
            </p>
        </p>
    </form>
    <p>
        <a href="http://www.fbloghost.com/plano-wp-host/" target="_blank" title="FBlogHost - Hospedagem profissional para Worpdress">
            <img style="border:none;" src="<?php echo $scp_plugin_dir; ?>/fbloghost.jpg" alt="FBlogHost - Hospedagem profissional para Worpdress" />
        </a>
    </p>
<?php
}
// Facebook Count
function get_scp_facebook(){
    $fb_id = get_option('scp_facebook');
    $count = get_transient('fan_count');
    if ($count !== false) return $count;
    $count = 0;
    $data = wp_remote_get("http://api.facebook.com/restserver.php?method=facebook.fql.query&query=SELECT%20fan_count%20FROM%20page%20WHERE%20page_id=$fb_id");
    if (is_wp_error($data)) {
        return '0';
    }
    else {
        $count = strip_tags($data[body]);
    }
    set_transient('fan_count', $count, 60*60*24); // 24 hour cache
    return $count;
}
// Twitter Count
function get_scp_twitter(){
    $tw_user = get_option('scp_twitter');
    $count = get_transient('follower_count');
    if ($count !== false) return $count;
    $count = 0;
    $data = wp_remote_get("http://api.twitter.com/1/users/show.json?screen_name=$tw_user");
    if (is_wp_error($data)) {
        return '0';
    }
    else {
        $value = json_decode($data['body'],true);
        $count = $value['followers_count'];
    }
    set_transient('follower_count', $count, 60*60*24); // 24 hour cache
    return $count;
}
// Feedburner Count
function get_scp_feedburner(){
    $feed_user = get_option('scp_feed');
    $count = get_transient('feed_count');
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
    set_transient('feed_count', $count, 60*60*24); // 24 hour cache
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
    set_transient('posts_count', $count, 60*60*24); // 24 hour cache
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
?>
    Facebook: <?php echo get_scp_facebook(); ?><br />
    Twitter: <?php echo get_scp_twitter(); ?><br />
    Feed: <?php echo get_scp_feedburner(); ?><br />
    Posts: <?php echo get_scp_posts(); ?><br />
    Coment&aacute;rios: <?php echo get_scp_comments(); ?>
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
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:'); ?> <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo attribute_escape($title); ?>" /></label></p>
        <?php
    }
    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = $new_instance['title'];
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
?>