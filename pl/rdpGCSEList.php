<?php
if(!class_exists('RDP_LFI_List_Table')){
    require_once(ABSPATH . 'wp-admin/includes/template.php' );
    require_once( 'class-wp-list-table.php' );
}
class RDP_GCS_CSE_List extends RDP_GCS_List_Table {
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'gcse',     //singular name of the listed records
            'plural'    => 'gcses',    //plural name of the listed records
            'ajax'      => false,        //does this table support ajax?
            'screen'      => 'interval-list' 
        ) );
        
    }//__construct
    
    function column_default($item, $column_name){
        switch($column_name){
            case 'url':
            case 'menu_title':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    } //column_default    
    
    function column_name($item){
        //Build row actions
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&feed=%s">%s</a>',$_REQUEST['page'],'edit',$item['id'],__('Edit', RDP_GCS_PLUGIN::$plugin_slug)),
            'delete'    => sprintf('<a href="?page=%s&action=%s&feed=%s">%s</a>',$_REQUEST['page'],'delete',$item['id'],__('Delete', RDP_GCS_PLUGIN::$plugin_slug)),
        );
        
        return sprintf('%1$s%2$s',
            /*$1%s*/ $item['name'],
            /*$2%s*/ $this->row_actions($actions)
        );
    } //column_name
    
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  
            /*$2%s*/ $item['id']                
        );
    }//column_cb
    
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'name'     => __('Name',  RDP_GCS_PLUGIN::$plugin_slug),
            'menu_title'     => __('Menu Title',  RDP_GCS_PLUGIN::$plugin_slug),
            'url'    => __('JSON URL',  RDP_GCS_PLUGIN::$plugin_slug)
        );
        return $columns;
    }//get_columns
    
    function get_sortable_columns() {
        $sortable_columns = array(
            'name'     => array('name',true),     //true means it's already sorted
            'menu_title'    => array('menu_title',false)            
        );
        return $sortable_columns;
    }//get_sortable_columns   
    
    function single_row( $item ) {
            static $row_class = '';
            $row_class = ( $row_class == '' ? ' class="alternate"' : '' );
            echo '<tr' . $row_class . '>';
            echo $this->single_row_columns( $item );
            echo '</tr>';
    }//single_row
    
    function get_bulk_actions() {
        $actions = array(
            'delete'    => 'Delete'
        );
        return $actions;
    } //get_bulk_actions
    
    function process_bulk_action() {
        global $wpdb;
        //Detect when a bulk action is being triggered...
        if( 'delete'===$this->current_action() ) {
            $nIDs = $_GET['feed'];
            if(!is_array($nIDs))$nIDs = array($nIDs);            
            foreach($nIDs as $feed) {
                $wpdb->delete( RDP_GCS_CSE_TABLE, array( 'id' => $feed ) );
            }
        }
        
    } //process_bulk_action  
    
    /** ************************************************************************
     * REQUIRED! This is where you prepare your data for display. This method will
     * usually be used to query the database, sort and filter the data, and generally
     * get it ready to be displayed. At a minimum, we should set $this->items and
     * $this->set_pagination_args(), although the following properties and methods
     * are frequently interacted with here...
     * 
     * @global WPDB $wpdb
     * @uses $this->_column_headers
     * @uses $this->items
     * @uses $this->get_columns()
     * @uses $this->get_sortable_columns()
     * @uses $this->get_pagenum()
     * @uses $this->set_pagination_args()
     **************************************************************************/
    function prepare_items() {
        global $wpdb; 
        $per_page = 10;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $table_name = RDP_GCS_CSE_TABLE;
        $sSQL = sprintf("Select count(*)num from `%s`;", $table_name);
        $row = $wpdb->get_row($sSQL );
	$total_items = $row->num;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  
            'per_page'    => $per_page,                     
            'total_pages' => ceil($total_items/$per_page)
        ));        
        
        $current_page = $this->get_pagenum();
        $start = ($current_page - 1) * $per_page;
        $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'name'; 
        $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc';  
        $sSQL = "Select id, name, menu_title, url from `$table_name` Order By $orderby $order LIMIT $start, $per_page;";
        $this->items = $wpdb->get_results($sSQL,ARRAY_A );        
    }//prepare_items    
    
}//RDP_GCS_CSE_List
//Create an instance of our package class...
$oFeedsList = new RDP_GCS_CSE_List();
//Fetch, prepare, sort, and filter our data...
$oFeedsList->prepare_items();

echo '<style type="text/css">';
echo '.wp-list-table .column-name { width: 20%; }';
echo '.wp-list-table .column-menu_title { width: 20%; }';
echo '</style>';

echo '<div class="wrap">';

echo '<div id="icon-users" class="icon32"><br/></div>';
echo '<h2>';
_e("Google CSE List", RDP_GCS_PLUGIN::$plugin_slug); 
echo '<a href="admin.php?page=rdp-google-custom-search-new" class="add-new-h2">';
_e("Add New", RDP_GCS_PLUGIN::$plugin_slug);
echo '</a></h2>';
echo '<form id="gcse-filter" method="get">';
echo '<input type="hidden" name="page" value="' . $_REQUEST['page'] . '" />';
$oFeedsList->display();
echo '</form>';
echo '</div>';

/* EOF */
