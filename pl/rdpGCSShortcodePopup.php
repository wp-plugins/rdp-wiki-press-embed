<?php

class RDP_GCS_SHORTCODE_POPUP {
    public static function addMediaButton($context){
	global $post, $pagenow;
	
	if ( in_array( $pagenow, array( "post.php", "post-new.php" ) ) && in_array( $post->post_type , array( "post", "page" ) ) ) {
            $rdp_lig_button_src = plugins_url('/images/rdp-gcs.ico', __FILE__);
	    $output_link = '<a href="#TB_inline?width=400&inlineId=rdp-gcs-shortcode-popup" class="thickbox button" title="RDP Google Custom Search" id="rdp-gcs-shortcode-button">';
            $output_link .= '<span class="wp-media-buttons-icon" style="background: url('. $rdp_lig_button_src.'); background-repeat: no-repeat; background-position: left bottom;"/></span>';
            $output_link .= '</a>';
            return $context.$output_link;
	} else {
            return $context;
	}        
    }//addMediaButton
    
    public static function renderPopupForm(){
        echo '<div id="rdp-gcs-shortcode-popup" style="display:none;">';
        echo '<h3>';
        _e('Insert Google Custom Search Shortcode',RDP_GCS_PLUGIN::$plugin_slug);
        echo '</h3>';
        
        echo '<lable for="rdp-gcs-cses">';
         _e('Select Custom Search Engines to Include',RDP_GCS_PLUGIN::$plugin_slug);
        echo '</lable>';
        echo '<div id="rdp-gcs-cses" class="container">';
        global $wpdb;
        $sSQL = "Select id, name, menu_title, url from `".RDP_GCS_CSE_TABLE."` Order By name;";
        $results = $wpdb->get_results( $sSQL );        
        foreach ( $results as $row ) 
        {
            echo '<input class="rdp-gcs-cse" name="rdp-gcs-cse" value="'. $row->id . '" data-menu-title="' . $row->name . '" type="checkbox" />' . $row->name . '<br/>';
        }
        echo '</div><!-- #rdp-gcs-cses -->';
        
        echo '<div id="rdp-gcs-cse-default-wrapper" class="hide" style="display:none;">';
        echo '<lable for="rdp-gcs-default-cse">';
         _e('Select the Default Custom Search Engine to Use',RDP_GCS_PLUGIN::$plugin_slug);
        echo '</lable><br />';
        echo '<select id="rdp-gcs-default-cse"></select>';
        echo '</div><!-- .rdp-gcs-cse-default-wrapper -->';
        
        echo '<div>&nbsp;</div>';
        echo '<input type="button" value="Insert into Post/Page" id="btnInsertGCSShortcode" class="button-primary">';
        echo '</div><!-- #rdp-gcs-shortcode-popup -->';
        
        $script_src = plugins_url('/js/script.shortcode-popup.js', __FILE__);                
        wp_enqueue_script('rdp-gcs-shortcode',$script_src, array('jquery'));
    }    
    
}//RDP_GCS_SHORTCODE_POPUP

/* EOF */
