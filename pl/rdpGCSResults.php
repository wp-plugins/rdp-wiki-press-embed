<?php if ( ! defined('WP_CONTENT_DIR')) exit('No direct script access allowed'); ?>
<?php
/**
 * RDP Google Custom Search Results Template
 * File: rdpGCSResults.php
 *
 */
get_header();
echo '<div id="main-content" class="main-content rdp-gcs-results-page">';
echo '<div id="primary" class="content-area">';
echo '<div id="content" class="site-content" role="main">';
echo apply_filters('rdp_gcs_before_search_box', '');
global $wpdb;
$sSQL = "SELECT id, menu_title FROM `" . RDP_GCS_CSE_TABLE . "` ORDER BY menu_title;";
$results = $wpdb->get_results($sSQL);
echo $this->buildSearchBox($results,$this->_selectedID);
echo apply_filters('rdp_gcs_after_search_box', '');

echo apply_filters('rdp_gcs_before_search_results', '');
echo apply_filters('rdp_gcs_render_search_results', $this->_searchResults,$this->_lastError);;
echo apply_filters('rdp_gcs_after_search_results', '');
echo '</div><!-- #content -->';
echo '</div><!-- #primary -->';
echo '</div><!-- #main-content -->';
get_footer();
/* EOF */
