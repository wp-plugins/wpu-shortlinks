<?php 
/*
Plugin Name: WPU Shortlinks
Plugin URI: http://wpu.ir
Description: Allows automatic url shortening of post links using wpu.ir Services using the API recently provided by WP-Parsi.
Author: Parsa Kafi
Version: 0.1.2
Author URI: http://parsa.ws
*/

if(!isset($_SESSION))
	session_start();

define("WPU_API_VERSION","1.0");
$WPU_API_URL = 'http://wpu.ir/ws/?api='.WPU_API_VERSION.'&ou=';   

add_action('publish_post', 'wpu_get_with_post');
add_action('edit_post', 'wpu_get_with_post');
add_action('save_post', 'wpu_get_with_post');
//add_action( 'load-post.php', 'wpu_add_custom_box' );
//add_action( 'load-post-new.php', 'wpu_add_custom_box' );
add_action('post_submitbox_misc_actions', 'wpu_publish_widget' );
add_action('init', 'wpu_tinymce_botton');
add_shortcode('wpu', 'wpu_shortcode' );

function wpu_shortlink($post_id=NULL,$display=true){
	global $post;
	if($post_id==NULL)
		$post_id = $post->ID;
	
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	if($display)
		echo $wpu_shortlink;
	else
		return $wpu_shortlink;
}

function wpu_shortcode($attr, $content = null) {
	global $post;
	
	extract( shortcode_atts( array(
		'id' => $post->ID
	), $attr ) );
	
	$post_id = $id;
	
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	if($wpu_shortlink){
		if(! empty($content))
			$shortlink_text = $content;
		else
			$shortlink_text = "پیوند کوتاه";
		
		$result = '<a href="'.$wpu_shortlink.'" target="_blank">'.$shortlink_text.'</a>';
	}
	
	return $result;
}

function wpu_tinymce_botton() {  
   if ( current_user_can('edit_posts') &&  current_user_can('edit_pages') ){  
	 add_filter('mce_external_plugins', 'wpu_mce_external_plugins');  
	 add_filter('mce_buttons', 'wpu_mce_buttons');  
   }  
}  

function wpu_mce_buttons($buttons) {
	array_push($buttons, "wpu_shortcode");
	return $buttons;  
}
function wpu_mce_external_plugins($plugin_array) {  
   $plugin_array['wpu_shortcode'] = plugins_url('wpu-shortlinks/wpu-shortcode.js');
   return $plugin_array;  
}

function wpu_publish_widget($post){
	global $post;
	$post_id = $post->ID;
	
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	$out = '<div style="margin:5px 10px">';
	$out .= 'لینک کوتاه: <input type="text" name="wpu_shortlink" value="'. $wpu_shortlink .'" size="25" onfocus="this.select()" style="text-align:left;direction:ltr;" readonly />';
	if(! empty($wpu_shortlink))
		$out .= ' <a href="'.$wpu_shortlink.'" target="_blank">*</a>';
	
	if(! empty($_SESSION['wpu_error'])){
		$out .= "<br /><font color='#f00'>" . $_SESSION['wpu_error'] . "</font>";
		unset($_SESSION['wpu_error']);
	}
	$out .= '</div>';
	
	echo $out;
}

function wpu_get_shortlink($post){
	global $WPU_API_URL;
	
	if(is_numeric($post)){
		if(function_exists("wp_get_shortlink"))
			$post_url = wp_get_shortlink( $post );
		
		if(empty($post_url))
			$post_url = home_url( '/' ) . '?p=' . $post;
			
	}elseif(is_object($post)){
		$post = $post->ID;
		$post_url = home_url( '/' ) . '?p=' . $post;
	}else{
		$post_url = $post;
	}
	
	if(wpu_is_validurl($post)){
		$reUrl = $WPU_API_URL . $post ;
		
		if (ini_get('allow_url_fopen')) {
			if ($handle = @fopen($reUrl, 'r')) {
				$result = fread($handle, 4096);
				fclose($handle);
			}
			
		} elseif (function_exists('curl_init')) {
			$ch = curl_init($reUrl);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
			$result = @curl_exec($ch);
			curl_close($ch);
		}
		
		if ($result !== false) {			
			return result;
			
		}elseif(function_exists('wp_remote_post')){
			$response = wp_remote_post( $reUrl, array(
					'method' => 'GET',
					'timeout' => 20,
					'redirection' => 2,
					'httpversion' => '1.0',
					'blocking' => true
				)
			);
			
			if( is_wp_error( $response ) ) {
				$_SESSION['wpu_error'] = 'خطا در اتصال به سرویس!';
				
			} else {
				return $response['body'];
			}
		} else {
			$_SESSION['wpu_error'] = 'خطا در اتصال به سرویس!';
		}
	}	
}

function wpu_get_with_post($post_id){
	global $post;
	
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
	
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	if(empty($wpu_shortlink)){
		$result = wpu_get_shortlink($post_id);
		wpu_save_response($result , $post_id);
	}
}

function wpu_save_response($result , $post_id){
	if($result == 'URL is not valid!'){
		$_SESSION['wpu_error'] = 'لینک ارسالی اعتبار ندارد!';
							
	}elseif(wpu_is_validurl($result)){
		if(! add_post_meta($post_id, 'wpu_shortlink', $result , true)) {
			update_post_meta($post_id, 'wpu_shortlink', $result );
		}
	}else{
		$_SESSION['wpu_error'] = $result;
	}
}

function wpu_add_custom_box($post_type) {
	if( function_exists( 'add_meta_box' )) {
		$types = array('attachment', 'revision', 'nav_menu_item');
		if(! in_array($post_type, $types)){
			add_meta_box( 'wpu_shortlinks', 'کوتاه کننده لینک (<a href="http://wpu.ir">WPU.IR</a>)', 'wpu_custom_box', $post_type , 'advanced' );
		}
	}
}

function wpu_custom_box() {
	global $post;
	$post_id = $post->ID;
	
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	$out = 'لینک کوتاه: <input type="text" name="wpu_shortlink" value="'. $wpu_shortlink .'" size="40" onfocus="this.select()" style="text-align:left;direction:ltr;" readonly />';
	if(! empty($wpu_shortlink))
		$out .= ' <a href="'.$wpu_shortlink.'" target="_blank">*</a>';
	
	if(! empty($_SESSION['wpu_error'])){
		$out .= "<br /><font color='#f00'>" . $_SESSION['wpu_error'] . "</font>";
		unset($_SESSION['wpu_error']);
	}
	
	echo $out;
}

function wpu_is_validurl($url){
	$url = trim($url);
	if($url==NULL || $url=="")
		return false;
	
	if (preg_match("/\b(?:(?:https?|ftp):\/\/|www\.)[-a-z0-9+&@#\/%?=~_|!:,.;]*[-a-z0-9+&@#\/%=~_|]/i", $url)) {
	    return true;
	} else {
	    return false;
	}
}
?>
