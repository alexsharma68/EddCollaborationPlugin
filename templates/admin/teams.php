<form method="get" action="">
<div class="wrap">
<?php include('tab.php'); ?>
    <h2>Team Members</h2>
    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
    <?php
		$data=array();
		$rows=get_teams_as_workroom($params);
		$collaborators_index=0;
		foreach($rows as $records){
			$author_id = $records->Collaborator_Id;
			$status=$records->Invitation_Status;
			$item_id=$records->Item_Id;
			if($status!='Accepted' && $status!='Invited') continue 1;
			$collaborators_index++;
			$data[]=array(
				'ID'=>$author_id,
				'title'=>get_the_author_meta('display_name',$author_id),
				'status'=>$status,
				'workroom_id'=>$params
			);
		}
		
		
    $Workroom_List_Table = new Team_List_Table();
	$Workroom_List_Table->data=$data;
    $Workroom_List_Table->prepare_items();
	$Workroom_List_Table->display()
	?>
     </form>
</div>
<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Team_List_Table extends WP_List_Table {
    var $data = array();
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'movie',     //singular name of the listed records
            'plural'    => 'movies',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
            case 'name':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_title($item){
        $actions = array(
           // 'edit'      => sprintf('<a href="?page=%s&action=%s&movie=%s">Edit</a>',$_REQUEST['page'],'edit',$item['ID']),
           'delete'    => sprintf('<a href="JavaScript:void(0)" data-collaborator-id="'.$item['ID'].'" data-workroom-id="'.$item['workroom_id'].'" data-status="'.(($item['status']=='Invited')?'Cancel Invitation':'Delete Invitation').'" onClick="JavaScript:Edd.collaboration.TEAM_MEMBERS.EDIT_MEMBERS(this);">'.(($item['status']=='Invited')?'Cancel Invitation':'Delete').'</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
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
            'title'     => 'Name',
        );
        return $columns;
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',false),     //true means it's already sorted
        );
        return $sortable_columns;
    }
    function get_bulk_actions() {
        $actions = array(
            'delete_team'    => 'Delete'
        );
        return $actions;
    }
    function process_bulk_action() {
        if( 'delete'===$this->current_action() ) {
          
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