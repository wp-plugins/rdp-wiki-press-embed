<?php if ( ! defined('WP_CONTENT_DIR')) exit('No direct script access allowed'); ?>
<?php

class RDP_GCS {
    private $_version;
    private $_submenuHTML;
    private $_searchResults;
    private $_selectedID = 0;
    private $_selectedMenuTitle = '';
    private $_searchTerm = '';
    private $_currentPage = 1;
    private $_lastError = '';
    
    public function __construct( $version ) {
        $this->_version = $version;
        $this->_searchResults = '....the search results....';
        add_shortcode('rdp-gcs', array(&$this, 'shortcode'));
    }//__construct
    
    function init(){
        if(!has_filter('widget_text','do_shortcode'))add_filter('widget_text','do_shortcode',11);   
        if(isset($_POST['txtRDPGCSCSEID']))$this->_selectedID = $_POST['txtRDPGCSCSEID'];
        if(!is_numeric($this->_selectedID)) $this->_selectedID = 0;
        if($this->_selectedID < 0)$this->_selectedID = 0;         
        if(isset($_POST['txtRDPGCSMenuTitle']))$this->_selectedMenuTitle = $_POST['txtRDPGCSMenuTitle'];
        if(isset($_POST['txtRDPGCSSearchTerm']))$this->_searchTerm = $_POST['txtRDPGCSSearchTerm'];

        if(!empty($this->_searchTerm)){
            $currentPage = (isset($_POST['txtRDPGCSPage']))? $_POST['txtRDPGCSPage'] : 1;
            if(!is_numeric($currentPage)) $currentPage = 1;
            if($currentPage < 1)$currentPage = 1;  
            $this->_currentPage = $currentPage;            
            add_filter('rdp_gcs_render_search_results', array(&$this,'renderSearchResults'),10,2);
            do_action('rdp_gcs_render_search_results_applied');            
            $this->fetchSearchResults();
        }
    }//init
    
    public function renderSearchResults($results,$err){
        $sHTML = '<div id="cse">';
        if(empty($results)):
            $sHTML .= '<div class="alert">';
            $sHTML .= $err;
            $sHTML .= '</div></div><!-- #cse -->';
            return $sHTML;
        endif;
        $sHTML .= '<div class="gsc-control-cse gsc-control-cse-en">';
        $sHTML .= '<div class="gsc-control-wrapper-cse" dir="ltr">';
        $sHTML .= '<div class="gsc-results-wrapper-nooverlay gsc-results-wrapper-visible">';
        
        /* results metadata */
        $sHTML .= '<div class="gsc-above-wrapper-area">';
        $sHTML .= '<table cellspacing="0" cellpadding="0" class="gsc-above-wrapper-area-container">';
        $sHTML .= '<tbody><tr><td class="gsc-result-info-container">';
        $sHTML .= '<div class="gsc-result-info" id="resInfo-0">';
        $sMsg = 'No results found.';
        $nTotalResults = 0;
        if(!empty($results->searchInformation)){
            $sMsg = sprintf( _n( '1 result (%s seconds)', 'About %s results (%s seconds)', $results->searchInformation->totalResults, RDP_GCS_PLUGIN::$plugin_slug ), $results->searchInformation->formattedTotalResults, $results->searchInformation->formattedSearchTime );
            $nTotalResults = $results->searchInformation->totalResults;
            
        }
        $sHTML .= $sMsg;
        $sHTML .= '</div>';
        $sHTML .= '</td></tr></tbody>';
        $sHTML .= '</table>';        
        $sHTML .= '</div><!-- .gsc-above-wrapper-area -->';
        
        /* results */
        $sHTML .= '<div class="gsc-wrapper">';
        $sHTML .= '<div class="gsc-resultsbox-visible">';
        $sHTML .= '<div class="gsc-resultsRoot gsc-tabData gsc-tabdActive">';
        $sHTML .= '<div class="gsc-results gsc-webResult" style="display: block;">';
        
        
        if(!empty($results->items) && isset($results->items[0])){
            $item = $results->items[0];
            $sHTML .= $this->buildResultItem($item);
        }
        
        if(!empty($results->items) && count($results->items)>1){
            $sHTML .= '<div class="gsc-expansionArea">';
            
            for($x=1;$x<count($results->items);$x++){
                $item = $results->items[$x];
                $sHTML .= $this->buildResultItem($item);                
            }
            if($nTotalResults>10)$sHTML .= $this->renderPaging($results);
            $sHTML .= '</div><!-- .gsc-expansionArea -->';
        }
        
        $sHTML .= '</div><!-- .gsc-results -->';
        $sHTML .= '</div><!-- .gsc-resultsRoot -->';
        $sHTML .= '</div><!-- .gsc-resultsbox-visible -->';
        $sHTML .= '</div><!-- .gsc-wrapper -->';
        
        $sHTML .= '</div><!-- .gsc-results-wrapper-nooverlay -->';
        $sHTML .= '</div><!-- .sc-control-wrapper-cse -->';
        $sHTML .= '</div><!-- .gsc-control-cse -->';
        $sHTML .= '</div><!-- #cse -->';
        return $sHTML;
    }//renderSearchResults
    
    private function buildResultItem($item){
        $sHTML = '<div class="gsc-webResult gsc-result">';
        $sHTML .= '<div class="gs-webResult gs-result">';
        
        /* title */
        $sHTML .= '<div class="gsc-thumbnail-inside">';
        $sHTML .= '<div class="gs-title">';
        $sHTML .= '<a class="gs-title" dir="ltr" href="' . $item->link . '" target="_blank">';
        $sHTML .= $item->htmlTitle;
        $sHTML .= '</a><!-- .gs-title -->';
        $sHTML .= '</div><!-- .gs-title -->';
        $sHTML .= '</div><!-- .gsc-thumbnail-inside -->';
        
        /* url */
        $sHTML .= '<div class="gsc-url-top">';
        $sHTML .= '<div class="gs-bidi-start-align gs-visibleUrl gs-visibleUrl-short" dir="ltr">' . $item->displayLink . '</div>';
        $sHTML .= '<div class="gs-bidi-start-align gs-visibleUrl gs-visibleUrl-long" style="word-break:break-all;" dir="ltr">' . $item->htmlFormattedUrl . '</div>';
        $sHTML .= '</div><!-- .gsc-url-top -->';
        
        
        /* result table */
        $sHTML .= '<table class="gsc-table-result"><tbody><tr>';
        $sHTML .= '<td class="gsc-table-cell-thumbnail gsc-thumbnail">';
        
        $sSrc = '';
        try{
            if(!empty($item->pagemap)){
                if(isset($item->pagemap->cse_thumbnail[0])){
                    $sSrc = $item->pagemap->cse_thumbnail[0]->src;
                }            
                if(empty($sSrc) && isset($item->pagemap->cse_image[0])){
                    $sSrc = $item->pagemap->cse_image[0]->src;
                }
            }            
        } catch (Exception $e) {
            //ignore error
        }

        if(!empty($sSrc)):
            $sHTML .= '<div class="gs-image-box gs-web-image-box gs-web-image-box-portrait">';
            $sHTML .= '<a class="gs-image" href="' . $item->link . '" target="_blank" >';
            $sHTML .= '<img class="gs-image" src="' . $sSrc . '"/>';
            $sHTML .= '</a>';
            $sHTML .= '</div>';
        endif;
        
        $sHTML .= '</td><!-- .gsc-table-cell-thumbnail -->';
        $sHTML .= '<td class="gsc-table-cell-snippet-close">';
        $sHTML .= '<div class="gs-bidi-start-align gs-snippet" dir="ltr">';
        $sHTML .= $item->htmlSnippet;
        $sHTML .= '</div><!-- .gs-snippet -->';
        $sHTML .= '</td><!-- .gsc-table-cell-snippet-close -->';
        $sHTML .= '</tr></tbody></table><!-- .gsc-table-result -->';
        
        $sHTML .= '</div><!-- .gs-webResult -->';
        $sHTML .= '</div><!-- .gsc-webResult -->';
        return $sHTML;
    }//buildResultItem
    
    
    public function renderPaging($results){
        $sHTML = '<div class="gsc-cursor-box gs-bidi-start-align" dir="ltr">';
        $sHTML .= '<div class="gsc-cursor">';

        $nStart = $this->_currentPage - 5;
        if($nStart < 1) $nStart = 1;
        
        for($x = $nStart;$x<$nStart+10;$x++){
            $sHTML .= '<div class="gsc-cursor-page';
            if($x == $this->_currentPage)$sHTML .= ' gsc-cursor-current-page';
            $sHTML .= '" tabindex="0" data-page="'. $x .'">'. $x .'</div>';
        }

        $sHTML .= '</div><!-- .gsc-cursor -->';
        $sHTML .= '</div><!-- .gsc-cursor-box -->';
        return $sHTML;
    }//renderPaging 
    
    private function fetchSearchResults(){
        $this->_searchResults = null;
        $this->_lastError = '';
        $sSQL = "Select url From `" . RDP_GCS_CSE_TABLE . "` Where id = {$this->_selectedID};";
        global $wpdb;
        $sURL = $wpdb->get_var( $sSQL );
        if(empty($sURL)){
            $this->_lastError = 'Missing API JSON URL.';
            return;
        }
        $apiURL = remove_query_arg( 'q',  $sURL);
        $params['q'] = str_replace(' ', '+', $this->_searchTerm);
        $start = (($this->_currentPage - 1)*10)+1;
        $params['start'] = $start;
        $apiURL = add_query_arg($params,$apiURL);
        $response = wp_remote_get( $apiURL );
        
        if ( is_wp_error( $response ) ) {
            $this->_lastError = $response->get_error_message();
         } else {
            $this->_searchResults = json_decode(wp_remote_retrieve_body($response));
         }
    }//fetchSearchResults

    
    public function shortcode($atts){
        if(is_404())return '';        
        $oAtts = shortcode_atts(array(
            'id' => '',
            'default' =>  ''
            ), $atts); 
        if(empty($oAtts['id']))return '';

        global $wpdb;
        $sSQL = "SELECT id, menu_title FROM `" . RDP_GCS_CSE_TABLE . "` WHERE id IN ({$oAtts['id']}) ORDER BY menu_title;";
        $results = $wpdb->get_results($sSQL);
        if(!$wpdb->num_rows) return '';
        
        return $this->buildSearchBox($results,(int)$oAtts['default']);
    }//shortcode
    
    public function buildSearchBox($results,$cseID = 0){
        $sHTML = '';
        $nSelectedID = 0;
        $sSelectedMenuTitle = '';
        foreach ( $results as $row ) 
        {
            if(empty($nSelectedID) || $row->id == $cseID){
                $nSelectedID = $row->id;
                $sSelectedMenuTitle = $row->menu_title; 
            }
        }             

        $nGUID = uniqid();        
        $submenuItems = '';
        $nCount = count($results);

        $fFirst = true;
        foreach ( $results as $row ) 
        {
            $submenuItems .= '<p class="option ';
            $submenuItems .= sanitize_title($row->menu_title);
            if($fFirst){
                $submenuItems .= ' first';
                $fFirst = false;
            }
            if($nSelectedID == $row->id){
                $submenuItems .= ' selected highlighted';
            }
            $submenuItems .= '"><a class="rdp-gcs-cse option" name="rdp-gcs-cse" data-guid="' . $nGUID . '" data-id="'. $row->id . '">' . $row->menu_title . '</a></p>';
        }             

        $submenuItems .= '<p class="option"><a class="rdp-gcs-cse option" name="rdp-gcs-cse" data-guid="' . $nGUID . '" data-id="0">';
        $submenuItems .= __('Page',  RDP_GCS_PLUGIN::$plugin_slug);
        $submenuItems .= '</a></p>';

        $submenuHTML = <<<EOD
        <div id="rdp-gcs-sub-wrapper-{$nGUID}" class="rdp-gcs-sub-wrapper hidden">
            <div class="rdp-gcs-wrap">
            {$submenuItems}
            </div><!-- .rdp-gcs-wrap -->
        </div><!-- .rdp-gcs-sub-wrapper -->   

EOD;

        $this->_submenuHTML .= $submenuHTML;
        if(!has_action('wp_footer', array(&$this,'renderCSESubmenu'))){
            add_action('wp_footer', array(&$this,'renderCSESubmenu'));
        }


        $sHTML .= '<form role="search" action="" method="post" accept-charset="UTF-8" id="frmRDPGCSearch-' . $nGUID . '" name="frmRDPGCSearch" class="rdp-gcs-search-form">';
        $sHTML .= '<table><tr>';

        $sHTML .= '<td class="rdp-gcs-search-scope-button">';
        $sHTML .= '<div id="rdp-gcs-search-scope-button-' . $nGUID . '" class="rdp-gcs-search-scope-button" data-guid="' . $nGUID . '" data-form-id="frmRDPGCSearch-' . $nGUID . '" >'. $sSelectedMenuTitle . '</div>';
        $sHTML .= '</td>';

        $sHTML .= '<td class="rdp-gcs-search-box">';
        $sHTML .= '<div class="rdp-gcs-search-box-container">';
        $sHTML .= '<input name="txtRDPGCSSearchTerm" class="rdp-gcs-search-term" data-guid="' . $nGUID . '" type="text" value="'. $this->_searchTerm . '"/>';
        $sHTML .= '<input name="txtRDPGCSCSEID" id="txtRDPGCSCSEID" type="hidden" value="'. $nSelectedID . '"/>';
        $sHTML .= '<input name="txtRDPGCSMenuTitle" id="txtRDPGCSMenuTitle" type="hidden" value="'. $sSelectedMenuTitle . '"/>'; 
        $sHTML .= '<input name="txtRDPGCSPage" id="txtRDPGCSPage" type="hidden"  value="" />';
        $sHTML .= '</div><!-- .rdp-gcs-search-box-container -->';
        $sHTML .= '</td>';

        $sHTML .= '<td class="rdp-gcs-search-button">';
        $sHTML .= '<button name="btnGCSearch" value="Search" class="rdp-gcs-search-button" data-guid="' . $nGUID . '" type="button"><span>Search</span></button>';
        $sHTML .= '</td>';
        $sHTML .= '</tr></table>';
        $sHTML .= '</form><!-- .rdp-gcs-search-form -->';

        return $sHTML;        
    }//buildSearchBox
    
    public function renderCSESubmenu(){
        echo $this->_submenuHTML;
    }//renderUserActionsSubmenu    
    
    public function stylesEnqueue(){
        wp_enqueue_style(
                'gsc-default',
                plugins_url('style/gsc.default.css', __FILE__ ),
                array('gsc-default-en'),
                $this->_version
        );        

        wp_enqueue_style(
                'gsc-default-en',
                plugins_url('style/gsc.default-en.css', __FILE__ ),
                null,
                $this->_version
        );        
        wp_enqueue_style(
                'rdp-gcs-common',
                plugins_url('style/rdp-gcs.style.css', __FILE__ ),
                array('gsc-default','gsc-default-en'),
                $this->_version
        );      
        
        $filename = get_stylesheet_directory() . '/rdp-gcs.custom.css';
        if (file_exists($filename)) {
            wp_register_style( 'rdp-gcs-style-custom', get_stylesheet_directory_uri() . '/rdp-gcs.custom.css' );
            wp_enqueue_style( 'rdp-gcs-style-custom' );
        }         
        
        do_action('rdp_gcs_styles_enqueued');
    }//stylesEnqueue
    
    public function scriptsEnqueue(){
        if(!wp_script_is('jquery-position-calculator')){
            wp_enqueue_script(
                    'jquery-position-calculator',
                    plugins_url('js/position-calculator.min.js', __FILE__ ), 
                    array('jquery'), 
                    '1.1.2', 
                    true); 
        }
        wp_enqueue_script( 
                'jquery-highlight', 
                plugins_url( 'js/jquery.highlight.js' , __FILE__ ), 
                array( 'jquery' ), 
                '3.0', 
                true);    
        wp_enqueue_script( 
                'rdp-gcs-global', 
                plugins_url( 'js/script.global.js' , __FILE__ ), 
                array( 'jquery','jquery-position-calculator' ), 
                $this->_version, 
                true);         
        
        do_action('rdp_gcs_scripts_enqueued');
    }//scriptsEnqueue  
    
    public function handleTemplateSelection($template){
        if(!empty($this->_searchTerm)):
            $template = RDP_GCS_PLUGIN_BASEDIR . 'pl/rdpGCSResults.php';
            include $template;
            exit;        
        endif;
    }//handleTemplateRedirect
    
}//RDP_GCS

/* EOF */
