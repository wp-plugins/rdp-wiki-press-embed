var $j=jQuery.noConflict();
// Use jQuery via $j(...)

$j(document).ready(rdp_pp_overlay_onReady);

function rdp_pp_overlay_onReady(){
    $j('#mainContent .ready').removeClass('invisible');

    if(typeof rdp_we_ppe == 'undefined'){
        $j(".ppe-cover-link").colorbox({iframe:true, innerWidth: 960, innerHeight:"80%",transition:"none"})
        $j(".ppe-add-to-cart").colorbox({iframe:true, innerWidth: 960, innerHeight:"80%",transition:"none"})
    }else{

        $j(".ppe-cover-link").colorbox(
                                    {returnFocus:false,
                                    inline:true, 
                                    innerWidth: 960, 
                                    innerHeight:"80%",
                                    transition:"none"
                                    }); 

        $j(".ppe-add-to-cart").colorbox(
                                    {returnFocus:false,
                                    inline:true, 
                                    innerWidth: 960, 
                                    innerHeight:"80%",
                                    transition:"none"
                                    });                                   


        $j("#rdp-ppe-inline-content-link").colorbox(
                                        {returnFocus:false,
                                        inline:true, 
                                        innerWidth: 960, 
                                        innerHeight:"80%",
                                        transition:"none"
                                        }) ;
        $j( "#wiki-embed-tabs" ).tabs(); 
        
        $j(document).bind('cbox_complete', function(){
            setTimeout(function(){
                $j( "#wiki-embed-tabs .ppe-tab" ).height($j( "#cboxLoadedContent" ).height() - ($j( "#wiki-embed-tabs .ui-tabs-nav" ).height()*3));
            },300);  
        }); 
        
        $j(document).bind('cbox_cleanup', function(){
          $j("#rdp_ppe_inline_content_wrapper").html($j("#cboxLoadedContent").html());
          
        }); 
        
        $j(document).bind('cbox_closed', function(){
            setTimeout(function(){$j( "#wiki-embed-tabs" ).tabs();},300);
        });
    }
      

}//rdp_pp_overlay_onReady
