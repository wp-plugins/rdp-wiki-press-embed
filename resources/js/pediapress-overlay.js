var $j=jQuery.noConflict();
// Use jQuery via $j(...)


$j(document).ready(rdp_pp_overlay_onReady);


function rdp_pp_overlay_onReady(){
    $j(".ppe-cover-link").colorbox({iframe:true, innerWidth: 900, innerHeight:"80%",transition:"none"})
    $j(".ppe-add-to-cart").colorbox({iframe:true, innerWidth: 900, innerHeight:"80%",transition:"none"})
    if(typeof rdp_we_ppe != 'undefined'){
        $j("#rdp-ppe-inline-content-link").colorbox(
                                        {inline:true, 
                                        innerWidth: 900, 
                                        innerHeight:"80%",
                                        transition:"none",
					onCleanup:function(){$j("#rdp_ppe_inline_content_wrapper").html($j("#cboxLoadedContent").html());}
                                        })        
    }
}//rdp_pp_overlay_onReady