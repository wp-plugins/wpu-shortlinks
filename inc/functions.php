<?php
function wpu_getUrls($string){
    $regex = '/https?\:\/\/[^\" ]+/i';
    preg_match_all($regex, $string, $matches);
    return ($matches[0]);
}

function wpu_shortlink($post_id=NULL,$display=true){
	global $post;
	if($post_id==NULL)
		$post_id = $post->ID;
	
	$wpu_shortlink = wpu_get_post_meta($post_id, 'wpu_shortlink', true);
	
	if($display)
		echo $wpu_shortlink;
	else
		return $wpu_shortlink;
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
	
	
	if(wpu_is_validurl($post_url)){
		$post_url = str_replace(array("http://","https://"),"",$post_url);
		
		$reUrl = $WPU_API_URL . $post_url ;
		
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
			return $result;
			
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
				$_SESSION['wpu_error'] = __('Error in connect to webservice!','wpu_shortlinks');
				
			} else {
				return $response['body'];
			}
		} else {
			$_SESSION['wpu_error'] = __('Error in connect to webservice!','wpu_shortlinks');
		}
	}	
}

function wpu_save_response($result , $post_id){
	if($result == 'URL is not valid!'){
		$_SESSION['wpu_error'] = __('Link not validate!','wpu_shortlinks');
							
	}elseif(wpu_is_validurl($result)){
		if(! add_post_meta($post_id, 'wpu_shortlink', $result , true)) {
			update_post_meta($post_id, 'wpu_shortlink', $result );
		}
	}else{
		$_SESSION['wpu_error'] = $result;
	}
}

function wpu_is_validurl($url){
	$url = trim($url);
	if($url==NULL || $url=="")
		return false;
	
	if(filter_var($url, FILTER_VALIDATE_URL)){
	    return true;
	} else {
	    return false;
	}
}

function wpu_get_post_meta($post_id){
	$wpu_shortlink = get_post_meta($post_id, 'wpu_shortlink', true);
	
	if(wpu_is_validurl($wpu_shortlink))
		return $wpu_shortlink;
	else
		return;
}
