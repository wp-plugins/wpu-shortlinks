<?php 
/*
Plugin Name: WPU Shortlinks
Plugin URI: http://wpu.ir
Description: Allows automatic url shortening of post links using wpu.ir Services using the API recently provided by WP-Parsi.
Author: Parsa Kafi
Version: 1.1.1
Author URI: http://parsa.ws
Text Domain: wpu_shortlinks
Domain Path: /languages/
License: GPL v3

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


if ( ! defined( 'ABSPATH' ) ) {
    header( 'Status: 403 Forbidden' );
    header( 'HTTP/1.1 403 Forbidden' );
    exit;
}

if(!isset($_SESSION))
	session_start();

define("WPU_API_VERSION","1.0");
$WPU_API_URL = 'http://wpu.ir/ws/?api='.WPU_API_VERSION.'&ou=';   

require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."functions.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."bulkaction.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."social.php");
require_once(dirname(__FILE__).DIRECTORY_SEPARATOR."inc".DIRECTORY_SEPARATOR."settings.php");

if(isset($_POST['wpu_submit'])){
    update_option( "wpu_shortlinks_settings", $_POST);
}
$set = get_option( "wpu_shortlinks_settings" );

register_activation_hook( __FILE__, 'wpu_plugin_activate' );
add_action('publish_post', 'wpu_get_with_post');
add_action('edit_post', 'wpu_get_with_post');
//add_action('save_post', 'wpu_get_with_post');
//add_action( 'load-post.php', 'wpu_add_custom_box' );
//add_action( 'load-post-new.php', 'wpu_add_custom_box' );
add_action('post_submitbox_misc_actions', 'wpu_publish_widget' );
add_action('init', 'wpu_init');
add_action('admin_head', 'wpu_admin_head');
add_filter('manage_posts_columns', 'wpu_shortlinks_columns_head');  
add_action('manage_posts_custom_column', 'wpu_shortlinks_columns_content', 10, 2);
add_filter('the_content','wpu_content_urls',1000);
add_action('admin_menu', 'wpu_custom_menu_page');
add_shortcode('wpu', 'wpu_shortcode' );
if($set['admin_bar_box']){
    add_action('admin_bar_menu', 'wpu_admin_bar', 50);
    add_action('wp_head', 'wpu_load_jscss');
    add_action('admin_head', 'wpu_load_jscss');
    add_action('wp_footer', 'wpu_load_box');
    add_action('admin_footer', 'wpu_load_box');
    add_action( 'wp_ajax_wpu_shortlinks_get', 'wpu_shortlinks_get' );
}

function wpu_plugin_activate() {

    $set = array(
                   'shorturl_content' => 0,
                   'admin_bar_box' => 1,
                   'display_shortlink_content' => 0,
                   'dsc_type' => 'text',
                   'social_sharing_status' => 0,
                   'social_sharing_order' => 'after_shortlink',
                   'social_sharing_before_text' => '',
                   'social_sharing_load_icon_css' => 1,
                   'social_sharing_icon_size' => 16,
                   'social_sharing_load_popup_js' => 0,
                   'social_sharing_twitter_username' => '',
                );

    add_option( "wpu_shortlinks_settings",$set);
}

function wpu_custom_menu_page() {
	add_submenu_page('options-general.php',__('WPU Shortlinks','wpu_shortlinks'),__('WPU Shortlinks','wpu_shortlinks'),'manage_options','wpu_settings','wpu_settings');
}

function wpu_shortlinks_get(){
    $url = $_POST['url'];
    
    $surl = wpu_get_shortlink($url);
    $surl = (wpu_is_validurl($surl) ? $surl : "");
    
    echo $surl;
    
    exit;
}

function wpu_load_box(){
    if(is_admin() || ( ! is_admin() && is_admin_bar_showing())){
        ?>
        <div id="wpu_shortlink_box">

        </div>
        <div id="wpu_shortlink_box_overlay">
            <strong><?php _e('<a href="http://wpu.ir" target="_blank">WPU.IR</a> url shortner','wpu_shortlinks') ?></strong>
            <span class="hidebox">×</span>
            <hr>
            <?php _e('Paste your long URL Here:','wpu_shortlinks') ?>
            <br>
            <input type="text" id="wpu_shortlink_box_t" onfocus="this.select()"> <input type="button" id="wpu_shortlink_box_b" class="button button-primary button-large" value="<?php _e('Shorten URL','wpu_shortlinks') ?>">
            <br><br>

            <span class="spinner"><?php if(! is_admin()) _e('Please Wait ...','wpu_shortlinks'); ?></span>
            <div class="result">
                <?php _e('Shortlink: ','wpu_shortlinks'); ?><br>
                <div class="result_input"></div>
            </div>
        </div>
    <?php
    }
}

function wpu_load_jscss() {
    $set = get_option( "wpu_shortlinks_settings" );

    if(is_admin() || ( ! is_admin() && is_admin_bar_showing())){
        ?>
        <script type="text/javascript">
            var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
        wp_enqueue_style('wpu_style', plugins_url("wpu-shortlinks/css/wpu-style.css",dirname(__FILE__)), array(), '', 'screen');
        wp_enqueue_script('wpu_script', plugins_url("wpu-shortlinks/js/wpu-js.js",dirname(__FILE__)), array('jquery'), '');
    }
    if(is_single() && $set['social_sharing_status'] && $set['social_sharing_load_icon_css']){
        wp_enqueue_style('wpu_sc_style', plugins_url("wpu-shortlinks/css/sc-styles-".$set['social_sharing_icon_size'].".css",dirname(__FILE__)), array(), '', 'screen');
    }
    if(is_single() && $set['social_sharing_status'] && $set['social_sharing_load_popup_js']){
        wp_enqueue_script('wpu_sc_script', plugins_url("wpu-shortlinks/js/wpu-script.js",dirname(__FILE__)), array('jquery'), '');
    }


}

function wpu_admin_bar($wp_admin_bar){
    $args = array(
                    'id' => 'wpu_shortlink',
                    "href" => "#",
                    'title' => __('WPU Shortlinks','wpu_shortlinks'),
                    'meta' => array(
                                        'class' => 'wpu_shortlink_ab',
                                        'onclick' => 'wpu_load_box();'
                                    )
                );
    $wp_admin_bar->add_menu($args);
}

function wpu_content_urls($content){
    global $post;
    $set = get_option( "wpu_shortlinks_settings" );
    $post_id = $post->ID;

    if($set['social_sharing_status'] && $set['social_sharing_order']=="before_shortlink"){
        $content .= wpu_social_sharing(false);
    }

    if($set['display_shortlink_content']){
        $shortlink = wpu_get_post_meta($post_id);
        
        if(! empty($shortlink)){
            if($set['dsc_type']=="text")
                $content .= '<div class="wpu_shortlink_content">'. __('Shortlink: ','wpu_shortlinks') . '<span class="shortlink_text">' . $shortlink . '</span></div>';
            else
                $content .= '<div class="wpu_shortlink_content">'. __('Shortlink: ','wpu_shortlinks') . ' <input type="text" value="'.$shortlink.'" size="20" class="shortlink_textbox" readonly onfocus="this.select();" onmouseup="return false;" />' . '</div>';
        }
    }

    if($set['social_sharing_status'] && $set['social_sharing_order']=="after_shortlink"){
        $content .= wpu_social_sharing(false);
    }
    
    if(! $set['shorturl_content'])
        return $content;
        
    $post_id = $post->ID;
    $slc = array();
    $slc = get_post_meta($post_id, 'wpu_shortlinks_content', true);
    $slc = unserialize($slc);
    
    $urls = (array) $slc['urls'];
    $surls = (array) $slc['surls'];
    
    $content = str_replace($urls,$surls,$content);
    
    return $content;    
}

function wpu_shortlinks_columns_head($defaults) {  
    $defaults['wpu_shortlinks_column'] = __('Shortlink','wpu_shortlinks');  
    return $defaults;
}

function wpu_shortlinks_columns_content($column_name, $post_id) {  
	if ($column_name == 'wpu_shortlinks_column') {  
		$wpu_shortlink = wpu_get_post_meta($post_id);
		$wpu_shortlink_text = str_replace(array("http://","http://www"),'',$wpu_shortlink);
		
		if($wpu_shortlink){
			echo '<a href="'.$wpu_shortlink.'" target="_blank">'.$wpu_shortlink_text.'</a>';
		}
	}  
}  

function wpu_admin_head(){
	global $pagenow;
	if($pagenow == "post.php" || $pagenow == "post-new.php"){
		echo '
		<script language="javascript">
			var wpu_shortlinks_slidt = \''.__('Post ID Shortlink? (Optional)','wpu_shortlinks').'\';
			var wpu_shortlinks_slt = \''.__('Shortlink Title?','wpu_shortlinks').'\';
			var wpu_shortlinks_sltd = \''.__('Shortlink','wpu_shortlinks').'\';
			var wpu_shortlinks_ast = \''.__('Add Shortlink wpu.ir','wpu_shortlinks').'\';			
		</script>';	
	}
}

function wpu_init() {
	load_plugin_textdomain('wpu_shortlinks',false,dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	
	if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') ){  
		add_filter('mce_external_plugins', 'wpu_mce_external_plugins');  
		add_filter('mce_buttons', 'wpu_mce_buttons');  
	}  
}

function wpu_shortcode($attr, $content = null) {
	global $post;
	
	extract( shortcode_atts( array(
		'id' => $post->ID
	), $attr ) );
	
	$post_id = $id;
	
	$wpu_shortlink = wpu_get_post_meta($post_id);
	
	if($wpu_shortlink){
		if(! empty($content))
			$shortlink_text = $content;
		else
			$shortlink_text = __('Post Shortlink','wpu_shortlinks');
		
		$result = '<a href="'.$wpu_shortlink.'" target="_blank">'.$shortlink_text.'</a>';
	}
	
	return $result;
} 

function wpu_mce_buttons($buttons) {
	array_push($buttons, "wpu_shortcode");
	return $buttons;  
}

function wpu_mce_external_plugins($plugin_array) {  
   $plugin_array['wpu_shortcode'] = plugins_url('wpu-shortlinks/js/wpu-shortcode.js');
   return $plugin_array;  
}

function wpu_publish_widget($post){
	global $post;
	$post_id = $post->ID;
	
	$wpu_shortlink = wpu_get_post_meta($post_id);
		
	$out = '<div style="margin:5px 10px">';
	$out .=  __('Shortlink: ','wpu_shortlinks') . ' <input type="text" name="wpu_shortlink" value="'. $wpu_shortlink .'" size="25" onfocus="this.select()" style="text-align:left;direction:ltr;" readonly />';
	if(! empty($wpu_shortlink))
		$out .= ' <a href="'.$wpu_shortlink.'" target="_blank">*</a>';
	
	if(! empty($_SESSION['wpu_error'])){
		$out .= "<br /><font color='#f00'>" . $_SESSION['wpu_error'] . "</font>";
		unset($_SESSION['wpu_error']);
	}
	$out .= '</div>';
	
	echo $out;
}

function wpu_get_with_post($post_id){
	global $post;
	$set = get_option( "wpu_shortlinks_settings" );
	
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
	  return;
	  
	$pos = strpos($post->post_name, 'autosave');
	if ($pos !== false) {
		return false;
	}
	$pos = strpos($post->post_name, 'revision');
	if ($pos !== false) {
		return false;
	}
	
	$revision = wp_is_post_revision( $post_id );
	if ( $revision )	
		$post_id = $revision;
	$post = get_post( $post_id );
	$post_id = $post->ID;
	
	$wpu_shortlink = wpu_get_post_meta($post_id);
	
	if(empty($wpu_shortlink)){
		$result = wpu_get_shortlink($post_id);		
		wpu_save_response($result , $post_id);
	}
	
	
	if($set['shorturl_content']){
        if(! is_object($post))
            $post = get_post($post_id);
        $content_shortlinks = array();
        $content = $post->post_content;
        $content_urls = wpu_getUrls($content);


        if(count($content_urls)){
            $slc = array();
            $slc = get_post_meta($post_id, 'wpu_shortlinks_content', true);
            $slc = unserialize($slc);

            $urls = (array) $slc['urls'];
            $surls = (array) $slc['surls'];

            foreach($content_urls as $url){
                if(in_array($url,$urls)){
                    $key = array_search($url,$urls);
                    if(empty($surls[$key])){
                        $slink = wpu_get_shortlink($url);
                        $surls[$key] = (wpu_is_validurl($slink) ? $slink : "");
                    }

                }else{
                    $urls[] = $url;
                    $surls[] = (wpu_is_validurl($slink) ? $slink : "");
                }
            }

           $content_shortlinks = array('urls' => $urls , 'surls' => $surls);

           $content_shortlinks = serialize($content_shortlinks);
           if(! add_post_meta($post_id, 'wpu_shortlinks_content', $content_shortlinks , true)) {
                update_post_meta($post_id, 'wpu_shortlinks_content', $content_shortlinks );
            }

        }
    }
}

function wpu_add_custom_box($post_type) {
	if( function_exists( 'add_meta_box' )) {
		$types = array('attachment', 'revision', 'nav_menu_item');
		if(! in_array($post_type, $types)){
			add_meta_box( 'wpu_shortlinks',  __('Shortlinker ','wpu_shortlinks') . ' (<a href="http://wpu.ir">WPU.IR</a>)', 'wpu_custom_box', $post_type , 'advanced' );
		}
	}
}

function wpu_custom_box() {
	global $post;
	$post_id = $post->ID;
	
	$wpu_shortlink = wpu_get_post_meta($post_id);
	
	$out =  __('Shortlink: ','wpu_shortlinks') . ' <input type="text" name="wpu_shortlink" value="'. $wpu_shortlink .'" size="40" onfocus="this.select()" style="text-align:left;direction:ltr;" readonly />';
	if(! empty($wpu_shortlink))
		$out .= ' <a href="'.$wpu_shortlink.'" target="_blank">*</a>';
	
	if(! empty($_SESSION['wpu_error'])){
		$out .= "<br /><font color='#f00'>" . $_SESSION['wpu_error'] . "</font>";
		unset($_SESSION['wpu_error']);
	}
	
	echo $out;
}

?>