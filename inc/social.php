<?php


/**
 * Returns a string containing the sharing buttons HTML
 *
 * @param array $args
 * @return string
 */
function wpu_social_sharing_html( $args = array() ) {

    $set = get_option( "wpu_shortlinks_settings" );
    $defaults = array(
        'element' => 'div',
        'social_options' => 'twitter, facebook, googleplus',
        'twitter_username' => $set['social_sharing_twitter_username'],
        'before_text' => $set['social_sharing_before_text'],
        'twitter_text' => __( 'Twitter', 'wpu_shortlinks' ),
        'facebook_text' => __( 'Facebook', 'wpu_shortlinks' ),
        'googleplus_text' => __( 'Google+', 'wpu_shortlinks' ),
    );

    // create final arguments array
    $args = wp_parse_args( $args, $defaults );
    $args['social_options'] = array_filter( array_map( 'trim', explode( ',', $args['social_options'] ) ) );
    extract( $args );

    $title = urlencode( get_the_title() );

    $url = wpu_get_post_meta(get_the_ID());
    if(empty($url))
        $url = urlencode( get_permalink() );

    ob_start();
    ?>

    <<?php echo $element; ?> class="wpu_shortlinks_sc">
    <span class="wpuss-ask"><?php echo $before_text; ?></span>
    <?php foreach($social_options as $o) {
        switch($o) {
            case 'twitter':
                ?><a rel="external nofollow" class="wpuss-twitter" href="http://twitter.com/intent/tweet/?text=<?php echo $title; ?>&url=<?php echo $url; ?><?php if(!empty($twitter_username)) {  echo '&via=' . $twitter_username; } ?>" target="_blank"><span class="wpuss-icon-twitter"></span> <?php echo $twitter_text; ?></a> <?php
                break;

            case 'facebook':
                ?><a rel="external nofollow" class="wpuss-facebook" href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $url; ?>&p[summary]=<?php echo $title; ?>" target="_blank" ><span class="wpuss-icon-facebook"></span> <?php echo $facebook_text; ?></a> <?php
                break;

            case 'googleplus':
                ?><a rel="external nofollow" class="wpuss-googleplus" href="https://plus.google.com/share?url=<?php echo $url; ?>" target="_blank" ><span class="wpuss-icon-googleplus"></span> <?php echo $googleplus_text; ?></a> <?php
                break;
        }
    } ?>
    </<?php echo $element; ?>>
    <!-- / Social Sharing By Danny -->
    <?php
    $output = ob_get_contents();
    ob_end_clean();

    return $output;
}


function wpu_social_sharing($echo=true,$args=array()){
    $sc = wpu_social_sharing_html($args);

    if($echo)
        echo $sc;
    else
        return $sc;
}