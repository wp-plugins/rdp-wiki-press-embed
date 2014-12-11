<?php
if ( ! class_exists('RDP_WE_PPE') ) :
class RDP_WE_PPE {
    public static function shortcode_handler($url,$atts,$content = null){
        wp_enqueue_script( 'colorbox', plugins_url( '/resources/js/jquery.colorbox.min.js',RDP_WE_PLUGIN_BASENAME),array("jquery"), "1.3.20.2", true );        
        wp_enqueue_script( 'pp-overlay', plugins_url( '/resources/js/pediapress-overlay.js',RDP_WE_PLUGIN_BASENAME),array("jquery",'colorbox'), "1.0", true );        
        if(!empty($content)){
            $params = array('fcontent' => 1);
            wp_localize_script( 'pp-overlay', 'rdp_we_ppe', $params );
        } 

        wp_enqueue_style( 'wiki-embed-overlay', plugins_url( '/resources/css/colorbox.css',RDP_WE_PLUGIN_BASENAME),false, "1.3.20.2", 'screen');        
        $content = self::grabContentFromPediaPress($url,$atts,$content);
        return $content;
    } //shortcode
    
    
    public static function grabContentFromPediaPress($URL,$atts,$content = null){
        $html = null;
        $sHTML = '';
        require_once RDP_WE_PLUGIN_BASEDIR .'/resources/simple_html_dom.php'; 
        $html = rdp_file_get_html($URL);
        if(!$html)return $sHTML;  
        global $wikiembed_object;     
        $wikiembed_options = $wikiembed_object->options; 
        
        $a = shortcode_atts( array(
        'url' => '',
        'toc_show' => empty($wikiembed_options['toc-show'])? '0' : $wikiembed_options['toc-show'],
        'toc_links' => empty($wikiembed_options['toc-links'])? 'default' : $wikiembed_options['toc-links'],
        'cta_button_content' => empty($content)? empty($wikiembed_options['ppe-cta-button-content'])? '' : $wikiembed_options['ppe-cta-button-content'] : $content,
        'cta_button_text' => empty($wikiembed_options['ppe-cta-button-text'])? PPE_DOWNLOAD_BUTTON_TEXT : $wikiembed_options['ppe-cta-button-text'],
        'cta_button_width' => empty($wikiembed_options['ppe-cta-button-width'])? PPE_DOWNLOAD_BUTTON_WIDTH : $wikiembed_options['ppe-cta-button-width'],
        'cta_button_top_color' => empty($wikiembed_options['ppe-cta-button-top-color'])? PPE_DOWNLOAD_BUTTON_TOP_COLOR : $wikiembed_options['ppe-cta-button-top-color'],
        'cta_button_font_color' => empty($wikiembed_options['ppe-cta-button-font-color'])? PPE_DOWNLOAD_BUTTON_FONT_COLOR : $wikiembed_options['ppe-cta-button-font-color'],
        'cta_button_font_hover_color' => empty($wikiembed_options['ppe-cta-button-font-hover-color'])? PPE_DOWNLOAD_BUTTON_FONT_HOVER_COLOR : $wikiembed_options['ppe-cta-button-font-hover-color'],
        'cta_button_border_color' => empty($wikiembed_options['ppe-cta-button-border-color'])? PPE_DOWNLOAD_BUTTON_BORDER_COLOR : $wikiembed_options['ppe-cta-button-border-color'],
        'cta_button_bottom_color' => empty($wikiembed_options['ppe-cta-button-bottom-color'])? PPE_DOWNLOAD_BUTTON_BOTTOM_COLOR : $wikiembed_options['ppe-cta-button-bottom-color'],
        'cta_button_box_shadow_color' => empty($wikiembed_options['ppe-cta-button-box-shadow-color'])? PPE_DOWNLOAD_BUTTON_BOX_SHADOW_COLOR : $wikiembed_options['ppe-cta-button-box-shadow-color'],
        'cta_button_text_shadow_color' => empty($wikiembed_options['ppe-cta-button-text-shadow-color'])? PPE_DOWNLOAD_BUTTON_TEXT_SHADOW_COLOR : $wikiembed_options['ppe-cta-button-text-shadow-color'],
        ), $atts );
        
        if(!is_numeric($a['toc_show']))$a['toc_show'] = 1;
        $arrTOCLinksDefaults = array('default','disabled','logged-in');
        if(!in_array( $a['toc_links'], $arrTOCLinksDefaults ))$a['toc_links'] = 'default';
        $sDownloadButton = '';
        $sInlineHTML = '';        
        $bodyID = $html->find('body',0)->id;
        $mainContent = $html->find('div#mainContent',0);
        $baseURL = 'https://pediapress.com';
        switch ($bodyID) {
            case 'book_category':
                foreach($mainContent->find('#category-tree li a') as $categoryLink){
                    $categoryLink->href = $baseURL . $categoryLink->href;
                }
                foreach($mainContent->find('div.s1 a') as $link){
                    if(substr($link->href, 0, 1)== '?'){
                        $link->href = $URL . $link->href;                        
                    }else{
                        $link->href = $baseURL . $link->href;                       
                    }

                }
                foreach ($mainContent->find('#bookList li img') as $coverImage) {
                    $coverImage->src = $baseURL . $coverImage->src;
                }
                break;
            case 'book_show':
                $mainContent->class = 'book_show';
                $coverImage = $mainContent->find('#coverImage',0);
                $coverImage->src = $baseURL . $coverImage->src;
                $coverImage->outertext = "<a href='{$URL}' target='_new' class='ppe-cover-link'>" . $coverImage->outertext . "</a>";
                
                if(empty($a['toc_show'])){
                    $mainContent->find('h2',0)->outertext = '';
                    $mainContent->find('ul.outline',0)->outertext = '';                    
                }else{
                    switch ($a['toc_links']) {
                        case 'logged-in':
                            if(!is_user_logged_in()):
                                foreach($mainContent->find('ul.outline li a') as $link){
                                    $link->href = null;
                                    $link->class = 'not-allowed';
                                }
                            endif;
                            break;
                        case 'disabled':
                            foreach($mainContent->find('ul.outline li a') as $link){
                                $link->href = null;
                                $link->class = 'not-allowed';
                            }
                            break;
                        default:
                            break;
                    }
                }
                
                
                $mainContent->find('#coverPreviewArea .ready p',0)->outertext = '';
                $mainContent->find('#coverPreviewArea noscript',0)->outertext = '';        
                foreach($mainContent->find('a.preview-link') as $element){
                    $element->href = null;
                } 
                
                $s1Divs = $mainContent->find('div.s1');
                for ($i = 0; $i < count($s1Divs);$i++ ){
                    if($i > 0)$s1Divs[$i]->outertext = '';
                }
                
                $sPriceCurrency = $mainContent->find('#price-currency',0)->innertext;
                $sPriceAmount = $mainContent->find('#price-amount',0)->innertext;
                $sAddToCart = "<div id='add-to-cart-box'><span class='btn'><a class='ppe-add-to-cart' target='_new' href={$URL}>Print Edition - {$sPriceCurrency} {$sPriceAmount}</a><div></div></span></div>";
                $mainContent->find('#price-amount',0)->outertext = '';
                $mainContent->find('#price-star',0)->outertext = '';
                 
                $boundingBoxes = $mainContent->find('.boundingBox');
                for ($i = 0; $i < count($boundingBoxes);$i++ ){
                    if($i==0){
                        $boundingBoxes[$i]->outertext = ''; 
                    }else{
                        $boundingBoxes[$i]->style = null;
                        $boundingBoxes[$i]->class = 'boundingBoxes boundingBox' . $i;                        
                    }
                }
                
                $metaData = $mainContent->find('#metadata',0);
                $metaParas = $metaData->find('p');
                $metaParasContent = '';
                for ($i = 0; $i<=count($metaParas)-1;$i++ ){
                    $innerText = $metaParas[$i]->innertext;
                    if(strpos($innerText, 'Categories:') !== false){
                        $metaParas[$i]->outertext = ''; 
                        continue;
                    }
                    
                    if(strpos($innerText, 'Wiki page:') !== false){
                        $metaParas[$i]->outertext = '';
                        continue;
                    }
                    
                    $metaParasContent .= $metaParas[$i]->outertext;
                }

                if(!empty($a['cta_button_content'])){
                    $sDownloadButton = "<div id='rdp-ppe-inline-content-sep'>OR</div>";
                    $sDownloadButton .= "<div><a id='rdp-ppe-inline-content-link' class='rdp-ppe-cta-button' href='#rdp_ppe_inline_content'>{$a['cta_button_text']}</a></div>";
                    $sInlineHTML .= "<div id='rdp_ppe_inline_content_wrapper' style='display:none'><div id='rdp_ppe_inline_content' style='padding:10px; background:#fff;'>";
                    $sInlineHTML .= do_shortcode($a['cta_button_content']);
                    $sInlineHTML .= "</div></div>";
                }                 
                $metaData->innertext = $metaParasContent . $sAddToCart . $sDownloadButton;
                $boundingBoxes[1]->outertext = $s1Divs[0]->outertext . $metaData->outertext; 
               break;

            default:
                break;
        }//switch ($bodyID)

        $sHTML = $mainContent->outertext . $sInlineHTML;
        $html->clear();
 
        $style = <<<EOS
<style type="text/css">
.rdp-ppe-cta-button {
	-o-box-shadow:inset 0px 1px 0px 0px {$a['cta_button_box_shadow_color']};
	-moz-box-shadow:inset 0px 1px 0px 0px {$a['cta_button_box_shadow_color']};
	-webkit-box-shadow:inset 0px 1px 0px 0px {$a['cta_button_box_shadow_color']};
	box-shadow:inset 0px 1px 0px 0px {$a['cta_button_box_shadow_color']};
	background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, {$a['cta_button_top_color']}), color-stop(1, {$a['cta_button_bottom_color']}) );
	background:-o-gradient( linear, left top, left bottom, color-stop(0.05, {$a['cta_button_top_color']}), color-stop(1, {$a['cta_button_bottom_color']}) );
	background:-moz-linear-gradient( center top, {$a['cta_button_top_color']} 5%, {$a['cta_button_bottom_color']} 100% );
	filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='{$a['cta_button_top_color']}', endColorstr='{$a['cta_button_bottom_color']}');
	background-color:{$a['cta_button_top_color']};
        background-image:linear-gradient(
                to bottom,
                {$a['cta_button_top_color']},
                {$a['cta_button_bottom_color']}
        );            
	-webkit-border-top-left-radius:8px;
	-moz-border-radius-topleft:8px;
	border-top-left-radius:8px;
	-webkit-border-top-right-radius:8px;
	-moz-border-radius-topright:8px;
	border-top-right-radius:8px;
	-webkit-border-bottom-right-radius:8px;
	-moz-border-radius-bottomright:8px;
	border-bottom-right-radius:8px;
	-webkit-border-bottom-left-radius:8px;
	-moz-border-radius-bottomleft:8px;
	border-bottom-left-radius:8px;
	text-indent:0px;
	border:1px solid {$a['cta_button_border_color']};
	display:inline-block;
	color:{$a['cta_button_font_color']};
	font-family:Arial;
	font-size:15px;
	font-weight:bold;
	font-style:normal;
	height:30px;
	line-height:30px;
	width:{$a['cta_button_width']}px;
	text-decoration:none;
	text-align:center;
	text-shadow:1px 1px 0px {$a['cta_button_text_shadow_color']};
}
.rdp-ppe-cta-button:hover{
    color: {$a['cta_button_font_hover_color']};
}
.rdp-ppe-cta-button:active {
	position:relative;
	top:1px;
}</style>   
   
   
EOS;
       
        
        
        return $sHTML . $style;
    }//grabContentFromPediaPress
    
}//RDP_WE_PPE
endif;

/* EOF */
