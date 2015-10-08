
<div class="wrap">
    <h2>Workrooms</h2>
    
    <?php
		$arg=array(
			'collaboration_status'=>get_transient('Filter_By')
		);
		$workrooms=get_workroom($arg);
		$workrooms_list=array();
		$data=array();
		foreach($workrooms as $value){
			if(in_array($params,$workrooms_list)) continue 1;
			$workrooms_list[]=$value->ID;
			$ass_id=get_post_meta($value->ID,'Unfinished_Item_ID',true);
			$ass_leader_id=get_post_field('post_author',get_parent_item($ass_id));
			$ass_leader=get_the_author_meta('first_name',$ass_leader_id).' '.get_the_author_meta('last_name',$ass_leader_id);
			$status=get_collaboration_status_by_main_item_id($value->ID);
			$data[]=array(
				'ID'=>$value->ID,
				'title'=>$value->post_title,
				'leader'=>get_the_author_meta('display_name',$value->post_author),
				'item'=>get_the_title(get_post_meta($value->ID,'Unfinished_Item_ID',true)),
				'status'=>$status,
			);
		}
		
		
  $Workroom_List_Table = new Workroom_List_Table();
$Workroom_List_Table->data=$data;
 $Workroom_List_Table->prepare_items();
	//$Workroom_List_Table->display();
	//$Workroom_List_Table->display_rows_or_placeholder();
	
	//$Workroom_List_Table->search_box('mmmmm','llkjk');
	extract( $Workroom_List_Table->_args );
	//$Workroom_List_Table->display_tablenav( 'top' );
	?>
    <form method="POST" action="">
    <?php
   $Workroom_List_Table->search_box('Search','s');
?>
</form>
     <div class="tablenav top">

         <div class="alignleft actions bulkactions">
         <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
         <?php  wp_nonce_field( 'bulk-' . $Workroom_List_Table->_args['plural'] ); ?>
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    
             <?php $Workroom_List_Table->get_bulk_actions(); ?>
             </form>
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
         <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php  wp_nonce_field( 'bulk-' . $Workroom_List_Table->_args['plural'] ); ?>
             <?php $Workroom_List_Table->get_bulk_actions(); ?>
             </form>
         </div>
 <?php
         $Workroom_List_Table->extra_tablenav( 'bottom' );
         $Workroom_List_Table->pagination( 'bottom' );
 ?>
 
         <br class="clear" />
    </div>
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
            'singular'  => 'workroom',     //singular name of the listed records
            'plural'    => 'workrooms',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'leader':
			case 'item':
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
        return sprintf('<a href="?page=collab_message&collab_id=%2$s">%1$s</a> <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
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
            'title'     => 'Workroom Name',
            'leader'  => 'Workroom Leader',
			'item'  => 'Associated Unfinished Item',
			'status'  => 'Collaboration Status'
        );
        return $columns;
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
            'leader'  => array('leader',false),
			'item'  => array('item',false),
			'status'  => array('status',false)
        );
        return $sortable_columns;
    }
    function get_bulk_actions() {
        $actions = array(
            'delete_workroom'    => 'Delete',
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
		<option value="delete_workroom">Delete</option>
	 </select>';
	 echo '<input type="submit" name="action_button" id="doaction" class="button action" value="Apply">';
  echo "</div><div class='alignleft actions'>";
  echo '<select name="filter" id="bulk-action-selector-top">
	<option value="-1" '.((get_transient('Filter_By')=='all' || get_transient('Filter_By')=='')?'selected="selected"':'').'>Show all</option>
	<option value="in_progress" '.((get_transient('Filter_By')=='in-progress')?'selected="selected"':'').'>In Process</option>
	<option value="in_dispute" '.((get_transient('Filter_By')=='in-dispute')?'selected="selected"':'').'>In Dispute</option>
	<option value="complete" '.((get_transient('Filter_By')=='complete')?'selected="selected"':'').'>Complete</option>
	</select>';
  echo '<input type="submit" name="filter_button" id="filter_button" class="button action" value="Filter">';
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