

    <form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>">
        <div class="wrap">
                <?php include('tab.php'); ?>
                <?php
					$posts = get_files($params);
					$data=array();
					foreach($posts as $value){
						$ass_id=get_post_meta($value->ID,'Workroom_Id',true);
						$workroom_name=get_the_title($ass_id);
						$data[]=array(
							'ID'=>$value->ID,
							'title'=>$value->post_title,
							'owner'=>get_the_author_meta('first_name',$value->post_author).' '.get_the_author_meta('last_name',$value->post_author),
						);
					}
					$Workroom_List_Table = new Files_List_Table();
					$Workroom_List_Table->data=$data;
					$Workroom_List_Table->prepare_items();
				?>
                <div id="nav-menus-frame">
                    
                    <div id="menu-management-liquid">
                        <div id="menu-management">
                            
                                <div class="menu-edit ">
                                    <div id="side-sortables" class="accordion-container">
                                        <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page" id="add-page">
                                                        <h3 class="accordion-section-title" tabindex="0" style="background-image:none;">
                                                            Existing Files
                                                        </h3>
                                                        <div class="accordion-section-content">
                                                            <div id="post-body">
                                                                <div id="post-body-content">
                                                                    <?php $Workroom_List_Table->display(); ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                        </ul>
                                    </div> 
                                </div>
                            <!-- /#update-nav-menu -->
                        </div><!-- /#menu-management -->
                    </div><div id="menu-settings-column" class="metabox-holder">
                        <div class="clear"></div>
                            <div id="side-sortables" class="accordion-container">
                                <ul class="outer-border">
                                
                                            <li class="control-section accordion-section  open add-page" id="add-page">
                                            <h3 class="accordion-section-title" tabindex="0" style="background-image:none;">
                                                Add New File
                                            </h3>
                                            <div class="accordion-section-content">
                                            <label for="fl">Choose File to upload :</label>
                                            <?php
											$Alterupload='document.location.reload()';
											?>
                                            <?php include('uploader.php'); ?>
                                            
                                            
                                            
                                            
                                            
                                            </div>
                                        </li>
                                </ul>
                            </div>
                    </div>
                    <input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
                </div>
        </div>
    </form>
<?php
if(!class_exists('WP_List_Table')){
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}
class Files_List_Table extends WP_List_Table {
    var $data = array();
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'file',     //singular name of the listed records
            'plural'    => 'files',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
           // case 'name':
			 case 'owner':
                return $item[$column_name];
            default:
                return print_r($item,true); //Show the whole array for troubleshooting purposes
        }
    }
    function column_title($item){
        
		
		 $actions = array(
            'download'      => sprintf('<a href="javascript:void(0);" data-file-id="'.$item['ID'].'" data-file-name="'.$item['title'].'" onClick="JavaScript:Edd.collaboration.FILES.DOWNLOAD_FILES(this);">Download</a>',$_REQUEST['page'],'download',$item['ID']),
            'delete'    => sprintf('<a href="JavaScript:void(0)"  data-file-id="'.$item['ID'].'" onClick="JavaScript:Edd.collaboration.FILES.DELETE_FILES(this);">Delete</a>',$_REQUEST['page'],'delete',$item['ID']),
        );
        
        //Return the title contents
        return sprintf('%1$s <span style="color:silver">(id:%2$s)</span>%3$s',
            /*$1%s*/ $item['title'],
            /*$2%s*/ $item['ID'],
            /*$3%s*/ $this->row_actions($actions)
        );
		
		
		
		
		
		
		
		
    }
    function column_cb($item){
        return sprintf(
            '<input type="checkbox" name="%1$s[]" value="%2$s" />',
            /*$1%s*/ $this->_args['singular'], 
            /*$2%s*/ $item['ID']  
        );
    }
    function get_columns(){
        $columns = array(
            'cb'        => '<input type="checkbox" />', //Render a checkbox instead of text
            'title'     => 'File Name',
            'owner'    => 'Posted By',
        );
        return $columns;
    }
    function get_sortable_columns() {
        $sortable_columns = array(
            'title'     => array('title',true),     //true means it's already sorted
			 'owner'     => array('owner',false),     //true means it's already sorted
        );
        return $sortable_columns;
    }
    function get_bulk_actions() {
        $actions = array(
           'delete_files'    => 'Delete'
        );
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