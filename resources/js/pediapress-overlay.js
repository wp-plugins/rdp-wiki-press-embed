var $j=jQuery.noConflict();
// Use jQuery via $j(...)


$j(document).ready(rdp_pp_overlay_onReady);


function rdp_pp_overlay_onReady(){
    $j(".ppe-cover-link").colorbox({iframe:true, innerWidth: 900, innerHeight:"80%",transition:"none",onLoad: function () { $j('#colorbox').show(); }})
    $j(".ppe-add-to-cart").colorbox({iframe:true, innerWidth: 900, innerHeight:"80%",transition:"none",onLoad: function () { $j('#colorbox').show(); }})
}