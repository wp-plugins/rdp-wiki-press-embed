var $j=jQuery.noConflict();
// Use jQuery via $j(...)

$j(document).ready(rdp_we_admin_overlay_onReady);

function rdp_we_admin_overlay_onReady(){
    $j( "#wiki-embed-tabs" ).tabs();
}//rdp_we_admin_onReady


function wiki_embed_insert_overlay_form(){
        var wikiEmbedUrl        = jQuery("#wiki-embed-src").attr('value');
        var wikiEmbedSource 	= ( jQuery("#wiki-embed-display-links").attr('checked') ? jQuery("#wiki-embed-display-links").attr('value') : "" );
        var wikiEmbedOverlay 	= ( jQuery("#wiki-embed-overlay").attr('checked')       ? jQuery("#wiki-embed-overlay").attr('value')       : "" );
        var wikiEmbedTabs 	= ( jQuery("input:radio[name=wiki-embed-tabs]:checked") ? jQuery("input:radio[name=wiki-embed-tabs]:checked").attr('value') : "" );
        var wikiEmbedNoEdit 	= ( jQuery("#wiki-embed-edit").attr('checked')          ? jQuery("#wiki-embed-edit").attr('value')          : "" );
        var wikiEmbedNoContents = ( jQuery("#wiki-embed-contents").attr('checked')      ? jQuery("#wiki-embed-contents").attr('value')      : "" );
        var wikiEmbedNoInfobox  = ( jQuery("#wiki-embed-infobox").attr('checked')       ? jQuery("#wiki-embed-infobox").attr('value')       : "" );
        var win = parent;

        win.send_to_editor( "[wiki-embed url='"+wikiEmbedUrl+"' "+ wikiEmbedSource + wikiEmbedOverlay + wikiEmbedTabs + wikiEmbedNoEdit + wikiEmbedNoContents + wikiEmbedNoInfobox +" ]" );
}

function pp_embed_send_to_editor(){
    var ppEmbedURL = jQuery("#pp-embed-src").val();
    var ppTOCShow =  ( jQuery("#pp-embed-display-toc").attr('checked') ? '1': "0" );
    var ppTOCLinks = '';
    if(ppTOCShow == '1'){
        var sTOCLinks = jQuery("input:radio[name=pp-embed-toc-links]:checked").val();
        ppTOCLinks = " toc_links='"+ sTOCLinks +"'";
    }
    var sCode = "[wiki-embed url='"+ppEmbedURL+"' toc_show='"+ ppTOCShow +"'"+ ppTOCLinks;   
    var ppButtonText = "";
    var ppButtonWidth = "";
    var ppButtonTopColor = "";
    var ppButtonBottomColor = "";
    var ppButtonFontColor = "";
    var ppButtonFontHoverColor = "";
    var ppButtonBorderColor = "";
    var ppButtonBoxShadowColor = "";
    var ppButtonTextShadowColor = "";
    var ppButtonContent = jQuery("#pp-embed-download-button-content").val();
    
    if(ppButtonContent){
        ppButtonText = " download_button_text='"+ jQuery("#pp-embed-download-button-text").val() +"'";
        ppButtonWidth = " download_button_width='"+ jQuery("#pp-embed-download-button-width").val() +"'";
        ppButtonTopColor = " download_button_top_color='"+ jQuery("#pp-embed-download-button-top-color").val() +"'";
        ppButtonBottomColor = " download_button_bottom_color='"+ jQuery("#pp-embed-download-button-bottom-color").val() +"'";
        ppButtonFontColor = " download_button_font_color='"+ jQuery("#pp-embed-download-font-color").val() +"'";
        ppButtonFontHoverColor = " download_button_font_hover_color='"+ jQuery("#pp-embed-download-button-font-hover-color").val() +"'";
        ppButtonBorderColor = " download_button_border_color='"+ jQuery("#pp-embed-download-button-border-color").val() +"'";
        ppButtonBoxShadowColor = " download_button_box_shadow_color='"+ jQuery("#pp-embed-download-button-box-shadow-color").val() +"'";
        ppButtonTextShadowColor = " download_button_text_shadow_color='"+ jQuery("#pp-embed-download-button-text-shadow-color").val() +"'";
        sCode += ppButtonText + ppButtonWidth + ppButtonTopColor + ppButtonFontColor + ppButtonFontHoverColor + ppButtonBorderColor + ppButtonBottomColor + ppButtonBoxShadowColor + ppButtonTextShadowColor;
    }

    if(ppButtonContent){
        sCode += "]" + ppButtonContent + "[/wiki-embed]"
    }else{
       sCode += " /]"; 
    }    
    
    var win = parent;    
    win.send_to_editor( sCode );
}