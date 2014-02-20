<?php
function wpu_settings(){
	
	$set = get_option( "wpu_shortlinks_settings" );

?>
<div class="wrap">
<div class="wpu_settings_title">
<a href="http://wpu.ir" target="_blank"><img src="<?php echo plugins_url("wpu-shortlinks/images/wp-parsi-logo.png",dirname(__FILE__)) ?>" alt="WP-Parsi"></a>
<h1> <?php _e('WPU Shortlinks','wpu_shortlinks') ?></h1>
</div>
<form action="" method="post">
<table width="100%" border="0" cellspacing="5" cellpadding="5">
<tr>
        <td width="200" scope="row"><?php _e('Generate Content URL Shortlinks','wpu_shortlinks') ?></td>
        <td><label><input type="checkbox" name="shorturl_content" value="1" <?php checked( $set['shorturl_content'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
</tr>
<tr>
        <td width="200" scope="row"><?php _e('Access With Admin Bar','wpu_shortlinks') ?></td>
        <td><label><input type="checkbox" name="admin_bar_box" value="1" <?php checked( $set['admin_bar_box'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
</tr>
<tr>
        <td width="200" scope="row"><?php _e('Display Shortlink in Post Content','wpu_shortlinks') ?></td>
        <td><label><input type="checkbox" name="display_shortlink_content" value="1" <?php checked( $set['display_shortlink_content'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
</tr>
<tr>
        <th><input type="submit" name="wpu_submit" value="<?php _e('Save','wpu_shortlinks') ?>" class="button" /></th>
        <td></td>
</tr>
</table>
</form>
</div>
<?php
}
