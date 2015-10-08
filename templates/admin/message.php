












<form method="POST" action="<?php echo admin_url( 'admin.php' ); ?>" name="MESSAGE_FORMS" id="MESSAGE_FORMS">
        <div class="wrap">
                <?php include('tab.php'); ?>
                <?php
						$args = array(
									'meta_query' => array(
									'relation' => 'AND',
										array(
											'key' => 'Workroom_Id',
											'value' =>array($params),
										),
									),
									'post_type' => 'collab-msg',
								);
						$message = new WP_Query( $args );
						$posts = $message->get_posts();
						$data=array();
						foreach($posts as $value){
							$ass_id=get_post_meta($value->ID,'Unfinished_Item_ID',true);
							$ass_leader_id=get_post_field('post_author',get_parent_item($ass_id));
							$ass_leader=get_the_author_meta('first_name',$ass_leader_id).' '.get_the_author_meta('last_name',$ass_leader_id);
							
							
							$attachs=get_post_meta($value->ID, '_wp_collaboration_attachments',true);
															
															$str='<div class="clear"></div>';
														   
															if(is_array($attachs) && sizeof($attachs)>0){
															
														   
														   $str.='<ul class="attachments">
																<li>
																	<a href="JavaScript:void(0)">Attachments</a>
																	<ul class="attached-list">';
																   
																	foreach($attachs as $atts){
																	$str.='<li>
																		<a  href="javascript:void(0);" data-file-id="'.$value->ID.'" data-file-name="'.$atts.'" onClick="JavaScript:Edd.collaboration.DASHBOARD.DOWNLOAD_ATTACHMENTS(this);">'.$atts.'</a>
																		</li>';
																		
																	}
																	$str.='</ul>
																</li>
															</ul>';
															}
															
							$data[]=array(
								'ID'=>$value->ID,
								'title'=>$value->post_content.$str.' <br/><span>Posted By</span> <a href="javascript:void(0);">'.get_the_author_meta('first_name',$value->post_author).' '.get_the_author_meta('last_name',$value->post_author).'</a>'
							);
						}
						
						
					$Workroom_List_Table = new Message_List_Table();
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
                                                            Messges
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
                    </div>
                    
                    <div id="menu-settings-column" class="metabox-holder">
                        <div class="clear"></div>
                            <div id="side-sortables" class="accordion-container">
                                <ul class="outer-border">
                                            <li class="control-section accordion-section  open add-page" id="add-page">
                                            <h3 class="accordion-section-title" tabindex="0" style="background-image:none;">
                                                Add New Message
                                            </h3>
                                            
                                            <div class="accordion-section-content">
                                            <label for="fl">Message :</label><br/>
                                            <textarea class="form-input" name="POST_MESSAGE" style="width:100%;"></textarea>
                                            <input type="hidden" name="Workroom_Id" value="<?php echo $params; ?>">
                                            <?php
											$Alterupload='document.location.reload()';
											?>
                                            <?php include('attachments.php'); ?>
                                            <div class="clear">&nbsp;</div>
                                            <input type="button" id="POST_MESSAGE_BUTTON" class="button button-primary button-large" value="Save Message">
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
class Message_List_Table extends WP_List_Table {
    var $data = array();
    function __construct(){
        global $status, $page;
                
        //Set parent defaults
        parent::__construct( array(
            'singular'  => 'message',     //singular name of the listed records
            'plural'    => 'messages',    //plural name of the listed records
            'ajax'      => false        //does this table support ajax?
        ) );
        
    }
    function column_default($item, $column_name){
        switch($column_name){
			case 'title':
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
        return sprintf('%1$s %3$s',
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
            'title'     => 'Message',
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
            'delete_message'    => 'Delete'
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