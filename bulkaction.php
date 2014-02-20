<?php
/*
	Copyright: © 2012 Justin Stern (email : justin@foxrunsoftware.net)
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


/*
    Edit by Parsa Kafi (http://parsa.ws)
    wpu shortlinks plugin : http://wordpress.org/extend/plugins/wpu-shortlinks/
*/

class wpu_shortlinks_bulk_action {

    public function __construct() {

        if(is_admin()) {
            // admin actions/filters
            add_action('admin_footer-edit.php', array(&$this, 'custom_bulk_admin_footer'));
            add_action('load-edit.php',         array(&$this, 'custom_bulk_action'));
            add_action('admin_notices',         array(&$this, 'custom_bulk_admin_notices'));
        }
    }


    /**
     * Step 1: add the custom Bulk Action to the select menus
     */
    function custom_bulk_admin_footer() {
        global $post_type;

        $types = array('attachment', 'revision', 'nav_menu_item');
        if(! in_array($post_type, $types)){
            ?>
                <script type="text/javascript">
                    jQuery(document).ready(function() {
                        jQuery("select[name='action']").append("<option value='wpu_get_shortlinks'><?php _e('Generate Shortlinks','wpu_shortlinks') ?></option>");
                    });
                </script>
            <?php
        }
    }


    /**
     * Step 2: handle the custom Bulk Action
     * 
     * Based on the post http://wordpress.stackexchange.com/questions/29822/custom-bulk-action
     */
    function custom_bulk_action() {
        global $typenow;
        $post_type = $typenow;

        $types = array('attachment', 'revision', 'nav_menu_item');
        if(! in_array($post_type, $types)){

            // get the action
            $wp_list_table = _get_list_table('WP_Posts_List_Table');  // depending on your resource type this could be WP_Users_List_Table, WP_Comments_List_Table, etc
            $action = $wp_list_table->current_action();

            $allowed_actions = array("wpu_get_shortlinks");
            if(!in_array($action, $allowed_actions)) return;

            // security check
            check_admin_referer('bulk-posts');

            // make sure ids are submitted.  depending on the resource type, this may be 'media' or 'ids'
            if(isset($_REQUEST['post'])) {
                $post_ids = array_map('intval', $_REQUEST['post']);
            }

            if(empty($post_ids)) return;

            // this is based on wp-admin/edit.php
            $sendback = remove_query_arg( array('exported', 'untrashed', 'deleted', 'ids'), wp_get_referer() );
            if ( ! $sendback )
                $sendback = admin_url( "edit.php?post_type=$post_type" );

            $pagenum = $wp_list_table->get_pagenum();
            $sendback = add_query_arg( 'paged', $pagenum, $sendback );

            switch($action) {
                case 'wpu_get_shortlinks':
                    $getshortlink = 0;
                    foreach( $post_ids as $post_id ) {

				        wpu_get_with_post($post_id);

                        $getshortlink++;
                    }

                    $sendback = add_query_arg( array('shortlink' => $getshortlink, 'ids' => join(',', $post_ids) ), $sendback );
                break;

                default: return;
            }

            $sendback = remove_query_arg( array('action', 'wpu_get_shortlinks', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status',  'post', 'bulk_edit', 'post_view'), $sendback );

            wp_redirect($sendback);
            exit();
        }
    }
    
    
    function custom_bulk_admin_notices() {
        global $post_type, $pagenow;

        if($pagenow == 'edit.php' && isset($_REQUEST['shortlink']) && (int) $_REQUEST['shortlink']) {
            $message = sprintf( _n( __('Generate Shortlinks.','wpu_shortlinks'), '%s '. __('Posts Generate Shortlinks.','wpu_shortlinks'), $_REQUEST['shortlink'] ), number_format_i18n( $_REQUEST['shortlink'] ) );
            echo "<div class=\"updated\"><p>{$message}</p></div>";
        }
    }
}


new wpu_shortlinks_bulk_action();