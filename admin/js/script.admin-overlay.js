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
    var ppEmbedURL = jQuery("#pp-embed-src").attr('value');
    var ppTOCShow =  ( jQuery("#pp-embed-display-toc").attr('checked') ? '1': "0" );
    var ppTOCLinks = '';
    if(ppTOCShow == '1'){
        sTOCLinks = jQuery("input:radio[name=wiki-embed-toc-links]:checked").attr('value');
        ppTOCLinks = " toc_links='"+ sTOCLinks +"'";
    }
    var win = parent;
    win.send_to_editor( "[wiki-embed url='"+ppEmbedURL+"' toc_show='"+ ppTOCShow +"'"+ppTOCLinks +" /]" );
}