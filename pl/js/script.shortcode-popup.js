var $j=jQuery.noConflict();
// Use jQuery via $j(...)

$j(document).ready(rdp_gcs_shortcode_popup_onReady);

function rdp_gcs_shortcode_popup_onReady(){
    $j('.wp-admin').on( "click", '#btnInsertGCSShortcode' , rdp_gcs_insertShortcode ); 
    $j('.rdp-gcs-cse').click(function( index ) {
        var n = jQuery(".rdp-gcs-cse:checked").length;
        var oDD = $j('#rdp-gcs-default-cse');  
        oDD.empty();
        if(n>=2){
            $j('#rdp-gcs-cse-default-wrapper').addClass('show').removeClass('hide').show();
            jQuery(".rdp-gcs-cse:checked").each(function( index ) {
                oDD.append($j("<option />").val($j( this ).val()).text($j( this ).data('menuTitle')));
            });            
         }else{
            $j('#rdp-gcs-cse-default-wrapper').addClass('hide').removeClass('show').hide();
        }
        
    })

}//rdp_gcs_shortcode_popup_onReady

function rdp_gcs_insertShortcode(){
    var sCSE = '';
    jQuery(".rdp-gcs-cse:checked").each(function( index ) {
        if(sCSE.length != 0)sCSE += ',';
        sCSE += $j( this ).val();
      });
    if(!sCSE)return;
    var sCode = "[rdp-gcs id='"+sCSE+"'";
    if($j('#rdp-gcs-cse-default-wrapper').hasClass('show')){
        sCode += " default='"+$j('#rdp-gcs-default-cse').val()+"'";
    }
    sCode += "]";
    var win = window.dialogArguments || opener || parent || top;    
    win.send_to_editor( sCode );
}//rdp_gcs_insertShortcode