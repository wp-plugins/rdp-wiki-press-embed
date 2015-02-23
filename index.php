<?php

/*
Plugin Name: RDP Google Custom Search
Plugin URI: http://robert-d-payne.com/
Description: Add Google custom search engines to a page/post using a shortcode
Version: 0.1.0
Author: Robert D Payne
Author URI: http://robert-d-payne.com/
License: GPLv2 or later
*/
if ( ! defined( 'WPINC' ) ) {
    die;
}
// Turn off all error reporting
//error_reporting(E_ALL^ E_WARNING);
global $wpdb;
define('RDP_GCS_PLUGIN_BASENAME', plugin_basename(__FILE__));
$dir = plugin_dir_path( __FILE__ );
define('RDP_GCS_PLUGIN_BASEDIR', $dir);
define('RDP_GCS_PLUGIN_BASEURL',plugins_url( null, __FILE__ ) );
define('RDP_GCS_CSE_TABLE', $wpdb->prefix . 'gcsefeeds');


class RDP_GCS_PLUGIN {
    public static $plugin_slug = 'rdp-google-custom-search';    
    public static $version = '0.1.0';
    protected $loader;
    
    public function __construct() {
        $this->load_dependencies();
        $this->define_front_hooks();
        $this->define_admin_hooks();
        $this->define_ajax_hooks();
    }
    
    private function load_dependencies() {
        require_once RDP_GCS_PLUGIN_BASEDIR . 'pl/rdpGCS.php'; 
        require_once RDP_GCS_PLUGIN_BASEDIR . 'pl/rdpGCSAdmin.php'; 
        require_once RDP_GCS_PLUGIN_BASEDIR . 'pl/rdpGCSShortcodePopup.php' ;
        // Must Use
        require_once RDP_GCS_PLUGIN_BASEDIR . 'bl/rdpGCSLoader.php';
        $this->loader = new RDP_GCS_LOADER();        
    }//load_dependencies  
    
    private function define_front_hooks(){
        if(is_admin())return; 
        if(defined( 'DOING_AJAX' ))return;
        $oGCS = new RDP_GCS(self::$version);
        $this->loader->add_action( 'init', $oGCS, 'init' );        
        $this->loader->add_action( 'wp_enqueue_scripts', $oGCS, 'stylesEnqueue' );
        $this->loader->add_action( 'wp_enqueue_scripts', $oGCS, 'scriptsEnqueue' );
        $this->loader->add_filter( 'template_redirect', $oGCS, 'handleTemplateSelection' );
    }//define_front_hooks   
    
    private function define_admin_hooks() {
        if(!is_admin())return;
        if(defined( 'DOING_AJAX' ))return;
        $oGCSAdmin = new RDP_GCS_ADMIN(self::$version);
        $this->loader->add_action( 'admin_enqueue_scripts', $oGCSAdmin, 'stylesEnqueue' );
        $this->loader->add_action( 'admin_enqueue_scripts', $oGCSAdmin, 'scriptsEnqueue' );

        add_action('admin_menu', 'RDP_GCS_ADMIN::add_menu_item');
        add_action( 'admin_footer', 'RDP_GCS_SHORTCODE_POPUP::renderPopupForm' );
        add_action( 'media_buttons_context', 'RDP_GCS_SHORTCODE_POPUP::addMediaButton' );         
    }//define_admin_hooks
    
    
    private function define_ajax_hooks(){
        if(!defined( 'DOING_AJAX' ))return;

    }//define_ajax_hooks 
    
    public function run() {
        $this->loader->run();
    }
    
    public static function install() {
        global $wpdb;

        $table_name = RDP_GCS_CSE_TABLE;

        $charset_collate = '';

        if ( ! empty( $wpdb->charset ) ) {
          $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if ( ! empty( $wpdb->collate ) ) {
          $charset_collate .= " COLLATE {$wpdb->collate}";
        } 

        $sql = "CREATE TABLE $table_name (
                id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
                name varchar(55) NOT NULL,
                menu_title varchar(55) NOT NULL,
                url varchar(255) NOT NULL,
                UNIQUE KEY id (id)
        ) $charset_collate;";    

        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql ); 
    }//install   

}//RDP_GCS_PLUGIN
register_activation_hook( __FILE__, array( 'RDP_GCS_PLUGIN', 'install' ) );
$oRDP_GCS_PLUGIN = new RDP_GCS_PLUGIN();
$oRDP_GCS_PLUGIN->run();






/* EOF */
