<?php
if ( ! class_exists('RDP_WE_PPE') ) :
class RDP_WE_PPE {
    public static function shortcode_handler($url){
        $content = self::grabContentFromPediaPress($url);
        return $content;
    } //shortcode
    
    
    public static function grabContentFromPediaPress($URL){
        $html = null;
        $sHTML = '';
        require_once RDP_WE_PLUGIN_BASEDIR .'/resources/simple_html_dom.php'; 
        $html = rdp_file_get_html($URL);
        if(!$html)return $sHTML;  
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
                
                
                $mainContent->find('#coverPreviewArea .ready p',0)->outertext = '';
                $mainContent->find('#coverPreviewArea noscript',0)->outertext = '';        
                foreach($mainContent->find('a.preview-link') as $element){
                    $element->href = null;
                } 
                
                $s1Divs = $mainContent->find('div.s1');
                for ($i = 0; $i < count($s1Divs);$i++ ){
                    if($i > 0)$s1Divs[$i]->outertext = '';
                }
                $mainContent->find('#add-to-cart-form span.btn',0)->innertext = "<a class='ppe-add-to-cart' target='_new' href={$URL}>Add to Cart</a><div></div>";
                
                $addToCartBox = '';
                 
                $boundingBoxes = $mainContent->find('.boundingBox');
                for ($i = 0; $i < count($boundingBoxes);$i++ ){
                    $boundingBoxes[$i]->style = null;
                    $boundingBoxes[$i]->class = 'boundingBoxes boundingBox' . $i;
                    
                    if($i==0){
                        $addToCartBox = $boundingBoxes[$i]->find('#add-to-cart-box',0)->outertext;
                        $boundingBoxes[$i]->outertext = ''; 
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
                $metaData->innertext = $metaParasContent . $addToCartBox;
                $boundingBoxes[1]->outertext = $s1Divs[0]->outertext . $metaData->outertext; 
               break;

            default:
                break;
        }//switch ($bodyID)

        $sHTML = $mainContent->outertext;
        $html->clear();
        return $sHTML;
    }//grabContentFromPediaPress
    
}//RDP_WE_PPE
endif;

/* EOF */
