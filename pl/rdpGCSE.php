<?php

class RDP_GCS_CSE {
    private $_result;
    private $_resultArray;
    private $_feedID;
    private $_action;
    
    function __construct(){
        $this->_feedID = (!empty($_REQUEST['feed'])) ? $_REQUEST['feed'] : 'new';
        $this->_action = (!empty($_REQUEST['action'])) ? $_REQUEST['action'] : 'none'; 
    } //__construct 
    
    function update(){
        if (!isset($_POST["Submit"]))return;
        global $wpdb;
        $data = array(
            "name" => $_POST["gcsName"],
            "menu_title" => $_POST["gcsMenuTitle"],
            "url" => $_POST["gcsURL"]
        );
        
        $format = '%s';
        echo '<div id="message" class="updated below-h2"><p>';
        if(is_numeric($this->_feedID)){
            $where = array("id" => $this->_feedID) ;
            $where_format = '%d';
            $wpdb->update( RDP_GCS_CSE_TABLE, $data, $where, $format, $where_format );
            _e("Custom Search Engine updated.", RDP_GCS_PLUGIN::$plugin_slug);
        }else{
            $wpdb->insert( RDP_GCS_CSE_TABLE, $data, $format );
            _e("Custom Search Engine added.", RDP_GCS_PLUGIN::$plugin_slug);
        }
        echo '</p></div>';
    }//update   
    
    function results_fetch(){
        if($this->_feedID == 'new') return;
        $sSQL = sprintf("Select * from `%s` Where id = %d;", RDP_GCS_CSE_TABLE,$this->_feedID);
        $this->_result = mysql_query( $sSQL,$this->_conn );

        if (!$this->_result) {
                _e( 'Could not run query:', RDP_GCS_PLUGIN::$plugin_slug ) . mysql_error();
                exit;
        }
        
        while(($this->_resultArray[] = mysql_fetch_assoc($this->_result)) || array_pop($this->_resultArray));
        
    }//results_fetch       
    
    function prepare_form(){
        global $wpdb;
        $this->_resultArray = array(
            "id" => 'new',
            "name" => '',
            "menu_title" => '',
            "url" => ''
        );   
        if($this->_action == 'new')return;
        $sSQL = sprintf("Select * from `%s` Where id = %d;", RDP_GCS_CSE_TABLE,$this->_feedID);        
        $result = $wpdb->get_row($sSQL,ARRAY_A);
        $this->_resultArray = array_merge($this->_resultArray, $result);        
    }//prepare_form  
    
    function display(){
        echo '<div class="wrap">';
        echo "<h2>";
        if($this->_action == 'new'){
            _e("Add New Google Custom Search Engine", RDP_GCS_PLUGIN::$plugin_slug);
        }else{
            _e("Editing Google Custom Search Engine", RDP_GCS_PLUGIN::$plugin_slug);
        }    
        echo "</h2>";
	echo '<form name="form1" method="post" action="">';
        echo '<input type="hidden" name="feed" value="' . esc_attr($this->_resultArray['id']) . '">';    
        echo '<table class="form-table" id="tblGCSE">';

        echo '<tr valign="top">';
        echo '<th scope="row" nowrap="nowrap">';
        _e("Name", RDP_GCS_PLUGIN::$plugin_slug);
        echo '</th>';
        echo '<td><input type="text" maxlength="55" name="gcsName" value="' . esc_attr($this->_resultArray['name']) . '" />';
        echo '</td>';
        echo '</tr>';  

        echo '<tr valign="top">';
        echo '<th scope="row" nowrap="nowrap">';
        _e("Menu Title", RDP_GCS_PLUGIN::$plugin_slug);
        echo '</th>';
        echo '<td><input type="text" maxlength="55" name="gcsMenuTitle" value="' . esc_attr($this->_resultArray['menu_title']) . '" />';
        echo '<p>- ';
        _e("Keep it short", RDP_GCS_PLUGIN::$plugin_slug);
        echo '</p>';        
        echo '</td>';
        echo '</tr>';  
        
        
        echo '<tr valign="top">';
        echo '<th scope="row" nowrap="nowrap">';
        _e("JSON URL", RDP_GCS_PLUGIN::$plugin_slug);
        echo '</th>';
        echo '<td><input type="text" maxlength="255" name="gcsURL" style="width: 100%;" value="' . esc_attr($this->_resultArray['url']) . '" /></td>';
        echo '</tr>'; 
        
        echo '</table>';
        echo '<p class="submit">';
        $sButtonText = __( 'Save Changes', RDP_GCS_PLUGIN::$plugin_slug );
        echo '<input type="submit" name="Submit" class="button-primary" value="' . esc_attr($sButtonText) .'" />';
        echo '</p>';        
        echo '</form>';
    }//display    
    
}//RDP_GCS_CSE
$obj = new RDP_GCS_CSE();
$obj->update();
$obj->prepare_form();
$obj->display();
/*  EOF */
