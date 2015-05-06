<?php
function wpu_settings(){
	
	$set = get_option( "wpu_shortlinks_settings" );

?>
    <style>
        .wpu_wrap {margin-top: 20px}
        .wpu_wrap .title img { margin: 0px 53px; }
        .wpu_wrap td{vertical-align: top}
    </style>
<div class="wpu_wrap">
<div class="title">
<a href="http://wpu.ir" target="_blank"><img src="<?php echo plugins_url("images/wp-parsi-logo.png",dirname(__FILE__)) ?>" alt="WP-Parsi"></a>
<h1> <?php _e('WPU Shortlinks','wpu_shortlinks') ?></h1>
</div>
<form action="" method="post" id="wpu_settings">
<table widtd="100%" border="0" cellspacing="5" cellpadding="5">
    <tr>
            <td widtd="200" scope="row"><?php _e('Generate Content URL Shortlinks','wpu_shortlinks') ?></td>
            <td><label><input type="checkbox" name="shorturl_content" value="1" <?php checked( $set['shorturl_content'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
    </tr>
    <tr>
            <td widtd="200" scope="row"><?php _e('Access With Admin Bar','wpu_shortlinks') ?></td>
            <td><label><input type="checkbox" name="admin_bar_box" value="1" <?php checked( $set['admin_bar_box'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
    </tr>
    <tr>
            <td widtd="200" scope="row"><?php _e('Display Shortlink in Post Content','wpu_shortlinks') ?></td>
            <td ><label><input type="checkbox" name="display_shortlink_content" value="1" <?php checked( $set['display_shortlink_content'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label>

                <p><?php _e('Type: ','wpu_shortlinks') ?> <label for="dsc_text"><input type="radio" id="dsc_text" name="dsc_type" value="text" <?php checked( $set['dsc_type'],"text" ); ?> /> <?php _e('Text','wpu_shortlinks') ?></label>
                &nbsp;&nbsp;<label for="dsc_textbox"><input type="radio" id="dsc_textbox" name="dsc_type" value="textbox" <?php checked( $set['dsc_type'],"textbox" ); ?>/> <?php _e('Textbox','wpu_shortlinks') ?></label></p>
            </td>
    </tr>
    <tr>
        <td><?php _e('Social Sharing','wpu_shortlinks') ?></td>
        <td></td>
    </tr>
    <tr>
        <td widtd="200" scope="row"><?php _e('Status','wpu_shortlinks') ?></td>
        <td><label><input type="checkbox" name="social_sharing_status" value="1" <?php checked( $set['social_sharing_status'], 1 ); ?>  /> <?php _e('Active','wpu_shortlinks') ?> </label></td>
    </tr>
    <tr valign="top">
        <td scope="row">
            <label><?php _e('Order', 'wpu_shortlinks'); ?></label>
        </td>
        <td>
            <select name="social_sharing_order" class="widefat">
                <option value="after_shortlink" <?php selected($set['social_sharing_order'], 'after_shortlink'); ?> ><?php _e('After Shortlink', 'wpu_shortlinks'); ?></option>
                <option value="before_shortlink" <?php selected($set['social_sharing_order'], 'before_shortlink'); ?> ><?php _e('Before Shortlink', 'wpu_shortlinks'); ?></option>
            </select>
        </td>
    </tr>

    <tr valign="top">
        <td scope="row">
            <?php _e('Text before links', 'wpu_shortlinks'); ?>
        </td>
        <td>
            <input type="text" name="social_sharing_before_text" class="widefat" placeholder="<?php _e('Share this post:','wpu_shortlinks') ?>" value="<?php echo esc_attr($set['social_sharing_before_text']); ?>">
            <small><?php _e('the text to show before the sharing links.', 'wpu_shortlinks'); ?></small>
        </td>
    </tr>

    <tr valign="top" class="row-load-icon-css">
        <td scope="row">
            <?php _e('Load Icon CSS?', 'wpu_shortlinks'); ?>
        </td>
        <td>
            <label><input type="radio" name="social_sharing_load_icon_css" value="1" <?php checked($set['social_sharing_load_icon_css'], 1); ?> > <?php _e('Yes'); ?></label> &nbsp;
            <label><input type="radio" name="social_sharing_load_icon_css" value="0" <?php checked($set['social_sharing_load_icon_css'], 0); ?> > <?php _e('No'); ?></label>
            <br>
            <small><?php _e('Adds simple but pretty icons to the sharing links.', 'wpu_shortlinks'); ?></small>
        </td>
    </tr>

    <tr valign="top" class="row-icon-size">
        <td scope="row">
            <label><?php _e('Icon Size', 'wpu_shortlinks'); ?></label>
        </td>
        <td>
            <select name="social_sharing_icon_size" class="widefat">
                <option value="16" <?php selected($set['social_sharing_icon_size'], 16); ?> ><?php _e('Small', 'wpu_shortlinks'); ?> - 16x16 <?php _e( 'pixels', 'wpu_shortlinks' ); ?></option>
                <option value="32" <?php selected($set['social_sharing_icon_size'], 32); ?> ><?php _e('Normal', 'wpu_shortlinks'); ?> - 32x32 <?php _e( 'pixels' , 'wpu_shortlinks'); ?></option>
            </select>
        </td>
    </tr>

    <tr valign="top">
        <td scope="row">
            <?php _e('Load Pop-Up JS?', 'wpu_shortlinks'); ?>
        </td>
        <td>
            <label><input type="radio" name="social_sharing_load_popup_js" value="1" <?php checked($set['social_sharing_load_popup_js'], 1); ?> > <?php _e('Yes'); ?></label> &nbsp;
            <label><input type="radio" name="social_sharing_load_popup_js" value="0" <?php checked($set['social_sharing_load_popup_js'], 0); ?> > <?php _e('No'); ?></label>
            <br>
            <small><?php _e("Open Popup Window Instead of New Tab or Window", 'wpu_shortlinks'); ?></small>
        </td>
    </tr>

    <tr valign="top">
        <td scope="row">
            <label><?php _e('Twitter Username', 'wpu_shortlinks'); ?></label>
        </td>
        <td>
            <input type="text" name="social_sharing_twitter_username" class="widefat" placeholder="wpparsi" value="<?php echo esc_attr($set['social_sharing_twitter_username']); ?>">
            <small><?php _e('Set this if you want to append "via @yourTwitterUsername" to tweets.', 'wpu_shortlinks'); ?></small>
        </td>
    </tr>
    <tr>
            <td><input type="submit" name="wpu_submit" value="<?php _e('Save','wpu_shortlinks') ?>" class="button" /></td>
            <td></td>
    </tr>
</table>
</form>

    <br/><br/>
    <a href="https://addons.mozilla.org/en-US/firefox/addon/wpu-shortlinks/" target="_blank"><?php _e('WPU Shortlinks Firefox Addon', 'wpu_shortlinks'); ?></a>
    <br/>
    <a href="https://wordpress.org/plugins/dvk-social-sharing/" target="_blank"><?php _e('Social Sharing module based by "Social Sharing by Danny" plugin', 'wpu_shortlinks'); ?></a>
    <br/><br/>
</div>
<?php
}
