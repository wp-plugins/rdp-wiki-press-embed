<?php if ( ! defined('WP_CONTENT_DIR')) exit('No direct script access allowed'); ?>
<?php

class RDP_GCS_ADMIN {
    private $version;

    public function __construct( $version ) {
            $this->version = $version;
    }

    public function stylesEnqueue() {
            wp_enqueue_style(
                    'rdp-gcs-admin',
                    plugin_dir_url( __FILE__ ) . 'style/rdp-gcs.admin.css',
                    null,
                    $this->version,
                    FALSE
            );
    }//stylesEnqueue

    public function scriptsEnqueue() {
        wp_enqueue_script('common');
        wp_enqueue_script('wp-lists');
        wp_enqueue_script('postbox');
        wp_enqueue_script( 'jquery' );        
        wp_enqueue_script(
                'rdp-gcs-admin',
                plugin_dir_url( __FILE__ ) . 'js/rdp-gcs.admin.js', 
                array('jquery'), 
                $this->version, 
                true);
    }//scriptsEnqueue 
    
    /*------------------------------------------------------------------------------
    Add admin menu
    ------------------------------------------------------------------------------*/
    static function add_menu_item()
    {
        if ( !current_user_can('activate_plugins') ) return;
        add_object_page( 'RDP Google Custom Search', 'RDP GCS', 'publish_posts', RDP_GCS_PLUGIN::$plugin_slug, 'RDP_GCS_ADMIN::generate_page',plugins_url('/rdp-google-custom-search/pl/images/menu-icon.gif') );
        $sAllText = __('All Search Engines',  RDP_GCS_PLUGIN::$plugin_slug);
        $sAllCSEText = __('All Google CSEs',  RDP_GCS_PLUGIN::$plugin_slug);
        add_submenu_page( RDP_GCS_PLUGIN::$plugin_slug, $sAllText, $sAllCSEText, "publish_posts", RDP_GCS_PLUGIN::$plugin_slug, "RDP_GCS_ADMIN::generate_page" );   
        $sAddNewTitle = __('Google Custom Search Engine',  RDP_GCS_PLUGIN::$plugin_slug);
        $sAddNewText = __('Add New Google CSE',  RDP_GCS_PLUGIN::$plugin_slug);
        add_submenu_page( RDP_GCS_PLUGIN::$plugin_slug, $sAddNewTitle, $sAddNewText, 'publish_posts', RDP_GCS_PLUGIN::$plugin_slug.'-new', 'RDP_GCS_ADMIN::add_new');
    } //add_menu_item

    
    static function generate_page()
    {   
        $sAction = (isset($_REQUEST['action']))? $_REQUEST['action'] : '';
        if($sAction == 'new' || $sAction == 'edit'){
            include('rdpGCSE.php');
        }else{
            include('rdpGCSEList.php');
        }
    } //generate_page    
    
     static function add_new(){
         $_REQUEST['action'] = 'new';
         RDP_GCS_ADMIN::generate_page();
     }        
        
}//RDP_GCS_ADMIN

/* EOF */
