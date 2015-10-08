
<div class="wrap">
    <h2>Reported Problems</h2>
    
    <?php
		$workrooms=get_reported_problems();
		$workrooms_list=array();
		$data=array();
		foreach($workrooms as $value){
			$reporter_id=get_post_field('post_author',$value->ID);
			$reported_by=get_the_author_meta('first_name',$reporter_id).' '.get_the_author_meta('last_name',$reporter_id);
			$status=get_collaboration_status_by_main_item_id($value->ID);
			$workroom_id=get_post_meta($value->ID, 'Workroom_Id',true);
			$data[]=array(
				'ID'=>$value->ID,
				'title'=>$value->post_title,
				'workroom_name'=>get_the_title($workroom_id),
				'workroom_id'=>$workroom_id,
				'reported_by'=>$reported_by,
				'status'=>get_post_meta($workroom_id, 'Collaboration_Status',true),
			);
		}
		
		
		$Workroom_List_Table = new Workroom_List_Table();
		$Workroom_List_Table->data=$data;
		$Workroom_List_Table->prepare_items();
		extract( $Workroom_List_Table->_args );
	?>
    <form method="POST" action="">
    <?php
    $Workroom_List_Table->search_box('Search','s');
	?>
</form>
<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
     <div class="tablenav top">

         <div class="alignleft actions bulkactions">
         
         <?php  wp_nonce_field( 'bulk-' . $Workroom_List_Table->_args['plural'] ); ?>
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    
             <?php $Workroom_List_Table->get_bulk_actions(); ?>

         </div>
 <?php
        $Workroom_List_Table->extra_tablenav('top');
         $Workroom_List_Table->pagination('top'); 
		 ?>
 
        <br class="clear" />
     </div>
    <table class="wp-list-table <?php echo implode( ' ', $Workroom_List_Table->get_table_classes() ); ?>">
     <thead>
     <tr>
         <?php $Workroom_List_Table->print_column_headers(); ?>
     </tr>
     </thead> 
     <tfoot>
     <tr>
         <?php $Workroom_List_Table->print_column_headers( false ); ?>
     </tr>
     </tfoot> 
     <tbody id="the-list"<?php if ( $singular ) echo " data-wp-lists='list:$singular'"; ?>>
         <?php $Workroom_List_Table->display_rows_or_placeholder(); ?>
     </tbody>
 </table>

     <div class="tablenav bottom">
 
         <div class="alignleft actions bulkactions">

    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php  wp_nonce_field( 'bulk-' . $Workroom_List_Table->_args['plural'] ); ?>
             <?php $Workroom_List_Table->get_bulk_actions(); ?>
             
         </div>
 <?php
         $Workroom_List_Table->extra_tablenav( 'bottom' );
         $Workroom_List_Table->pagination( 'bottom' );
 ?>
 
         <br class="clear" />
    </div>
    </form>
</div>
<?php

if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Workroom_List_Table extends WP_List_Table {
    var $data = array();
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'problem',     //singular name of the listed records
            'plural'    => 'problems',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'workroom_name':
            case 'reported_by':
			case 'status':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_title($item){
        $actions = array(
           // 'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
           // 'delete'    => sprintf('<a href="?page=%s&action=%s&movie=%s">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        return sprintf('<a href="?page=collab_details&report_id=%2$s&collab_id=%4$s">%1$s</a> <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions),
			/*$4%s*/ $item['workroom_id']
        );
    }
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'],  //Let's simply repurpose the table's singular label ("movie")
            /*$2%s*/ $item['ID']                //The value of the checkbox should be the record's id
        );
    }
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'Problem Title',
            'workroom_name'    => 'Workroom Name',
			'reported_by'  => 'Reported By',
			'status'  => 'Workroom Status'
        );
        return $columns;
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
            'workroom_name'    => array('workroom_name',false),
			'reported_by'  => array('reported_by',false),
			'status'  => array('status',false)
        );
        return $sortable_columns;
    }
    function get_bulk_actions() {
        $actions = array(
            'delete_problem'    => 'Delete',
        );
		//return $actions;
		$actions = array(
  // I don't need to delete post in my case
  //'delete'    => 'Delete'
  );

  // Display date
  // For this example, I use a fonction in class-wp-liste-table.php
  // that display months (I use my own function to display months but this one works for the example)
 echo "<div class='alignleft actions'>";
 echo '<select name="action" id="bulk-action-selector-top">
		<option value="-1" selected="selected">Bulk Actions</option>
		<option value="delete_problem">Delete</option>
	 </select>';
	 echo '<input type="submit" name="action_button" id="doaction" class="button action" value="Apply">';
 /* echo "</div><div class='alignleft actions'>";
  echo '<select name="filter" id="bulk-action-selector-top">
	<option value="-1" '.((get_transient('Filter_By')=='all' || get_transient('Filter_By')=='')?'selected="selected"':'').'>Show all</option>
	<option value="in_progress" '.((get_transient('Filter_By')=='in-progress')?'selected="selected"':'').'>In Process</option>
	<option value="in_dispute" '.((get_transient('Filter_By')=='in-dispute')?'selected="selected"':'').'>In Dispute</option>
	<option value="complete" '.((get_transient('Filter_By')=='complete')?'selected="selected"':'').'>Complete</option>
	</select>';
  echo '<input type="submit" name="filter_button" id="filter_button" class="button action" value="Filter">'; */
  echo "</div>";
  return $actions;

    }
    function process_bulk_action() {
        if( 'delete'===$this->current_action() ) {
          wp_die('Sorry you cant delete this item!');
		   //wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), stripslashes( $_SERVER['REQUEST_URI'] ) ) );
    		//exit;
        }
    }
    function prepare_items() {
        global $wpdb; //This is used only if making any database queries
        $per_page = 5;
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->process_bulk_action();
        $data = $this->data;
        function usort_reorder($a,$b){
            $orderby = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby'] : 'title'; //If no sort, default to title
            $order = (!empty($_REQUEST['order'])) ? $_REQUEST['order'] : 'asc'; //If no order, default to asc
            $result = strcmp($a[$orderby], $b[$orderby]); //Determine sort order
            return ($order==='asc') ? $result : -$result; //Send final sort direction to usort
        }
        usort($data, 'usort_reorder');
        $current_page = $this->get_pagenum();
        $total_items = count($data);
        $data = array_slice($data,(($current_page-1)*$per_page),$per_page);
        $this->items = $data;
        $this->set_pagination_args( array(
            'total_items' => $total_items,                  //WE have to calculate the total number of items
            'per_page'    => $per_page,                     //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items/$per_page)   //WE have to calculate the total number of pages
        ) );
    }


}
?>     