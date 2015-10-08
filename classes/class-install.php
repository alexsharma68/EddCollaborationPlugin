<?php
if ( !defined( 'ABSPATH' ) ) {
	exit;
}

class Collaboration_Install {

	public $toSet = array();
	public $fes_settings = array();

	public function init() {
		$list_collabs=get_option('collab_exclude_pages');
		if(!is_array($list_collabs)){
		$list_collabs=array();	
		}
		
		$exists = get_page_by_title('Collaboration');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);	
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-workroom-list"]',
			  'post_name'      => 'collaboration',
			  'post_title'     => 'Collaboration',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    => '0',
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$page_id   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$page_id;
		}
		//add_option('collab_exclude_pages',$list_collabs);
		//return false;
		
		$exists = get_page_by_path('collaboration/workroom');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-workroom"]',
			  'post_name'      => 'workroom',
			  'post_title'     => 'Workroom',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$page_id   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$page_id;
		}
		/*Add New workroom */
		$exists = get_page_by_path('collaboration/workroom/add-workroom');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-add-workroom"]',
			  'post_name'      => 'add-workroom',
			  'post_title'     => 'Add New Workroom',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$workroomnew   = wp_insert_post( $post );
			update_post_meta($workroomnew, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$workroomnew;
		}
		/* end */
		
		
		$exists = get_page_by_path('collaboration/workroom/message');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-workroom"]',
			  'post_name'      => 'message',
			  'post_title'     => 'Message',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$message_id   = wp_insert_post( $post );
			update_post_meta($message_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$message_id;
		}
		
		
		
		
		
		$exists = get_page_by_path('collaboration/workroom/files');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-file"]',
			  'post_name'      => 'files',
			  'post_title'     => 'Files',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			wp_delete_post('collaboration-files');//files
			$files_id   = wp_insert_post( $post );
			update_post_meta($files_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$files_id;
		}
		$exists = get_page_by_path('collaboration/workroom/terms-and-conditions');//get_page_by_title('Terms and Conditions');
		if(!isset($exists->ID)){
			$post = array(
				  'post_content'   =>'Terms and Conditions',
				  'post_name'      => 'terms-and-conditions',
				  'post_title'     => 'Terms and Conditions',
				  'post_status'    =>'publish',
				  'post_type'      => 'page',
				  'post_author'    => '0',
				  'ping_status'    =>'close',
				  'post_parent'    =>$page_id,
				  'menu_order'     =>'0',
				  'to_ping'        =>'',
				  'pinged'         => '',
				  'post_password'  =>'',
				  'comment_status' => 'closed',
				  'post_category'  =>'',
				  'page_template'=> 'edd-collaboration.php'
			);
			$terms_id   = wp_insert_post( $post );	
			update_post_meta($terms_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$terms_id;
		}
		$exists = get_page_by_path('collaboration/workroom/team-members');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-team-members"]',
			  'post_name'      => 'team-members',
			  'post_title'     => 'Team members',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$page_id_team   = wp_insert_post( $post );
			update_post_meta($page_id_team, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$page_id_team;
		}
		
		$exists = get_page_by_path('collaboration/workroom/team-members-invitation');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-team-members-invitation"]',
			  'post_name'      => 'team-members-invitation',
			  'post_title'     => 'Team members',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'post_parent'    =>$page_id,
			  'menu_order'     =>'0',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-collaboration.php'
			);
			$teaminvitatilnid   = wp_insert_post( $post );
			update_post_meta($teaminvitatilnid, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$teaminvitatilnid;
		}
		/* Commission */
		
		$exists = get_page_by_path('collaboration/workroom/team-commission');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-team-commission"]',
			  'post_name'      => 'team-commission',
			  'post_title'     => 'Team Commission',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'post_parent'    => $page_id,
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			  'page_template'  => 'edd-collaboration.php'
			);
			$team_commission   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$team_commission;
		}
		/* End Commission */
		/* Alert */
		
		$exists = get_page_by_path('collaboration/workroom/workroom-alerts');
		if (!isset($exists->ID)){
			//wp_delete_post($exists->ID,true);
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-workroom-alert"]',
			  'post_name'      => 'workroom-alerts',
			  'post_title'     => 'Alerts',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'post_parent'    => $page_id,
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			  'page_template'  => 'edd-collaboration.php'
			);
			$team_commission   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$team_commission;
		}
		/* End Alert */

		/* report a problem */
		
		$exists = get_page_by_path('collaboration/workroom/report-problem');
		if (!isset($exists->ID)){
			$post = array(
			  'post_content'   =>'[edd_collaborations template="frontend-collaboration-report-problem"]',
			  'post_name'      => 'report-problem',
			  'post_title'     => 'Report a Problem',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'post_parent'    => $page_id,
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			  'page_template'  => 'edd-collaboration.php'
			);
			$team_commission   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-collaboration.php');
			$list_collabs[]=$team_commission;
		}
		$exists = get_page_by_path('user-details');
		if (!isset($exists->ID)){
			$post = array(
			  'post_content'   =>'[edd_artist_details]',
			  'post_name'      => 'user-details',
			  'post_title'     => 'User Details',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			);
			$user   = wp_insert_post( $post );
			$list_collabs[]=$user;
		}
		$exists = get_page_by_path('forum');
		if (!isset($exists->ID)){ 
			$post = array(
			  'post_content'   =>'[edd_forum]',
			  'post_name'      => 'forum',
			  'post_title'     => 'Forum',
			  'post_status'    =>'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    =>'close',
			  'to_ping'        =>'',
			  'pinged'         => '',
			  'post_password'  =>'',
			  'comment_status' => 'closed',
			  'post_category'  =>'',
			  'page_template'=> 'edd-forum.php'
			);
			$page_id   = wp_insert_post( $post );
			update_post_meta($page_id, '_wp_page_template', 'edd-forum.php');
			$list_collabs[]=$page_id;
		}
		
		$exists = get_page_by_path('arts-detail');
		if (!isset($exists->ID)){
			$post = array(
			  'post_content'   =>'[edd_arts_detail]',
			  'post_name'      => 'arts-detail',
			  'post_title'     => 'Arts Detail',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'post_parent'    => '0',
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			  'page_template'  => 'edd-arts-detail.php'
			);
			$arts_detail   = wp_insert_post( $post );
			update_post_meta($arts_detail, '_wp_page_template', 'edd-arts-detail.php');
			$list_collabs[]=$arts_detail;
		
		}
		
		$exists = get_page_by_path('product-recommand');
		if (!isset($exists->ID)){
			$post = array(
			  'post_content'   =>'[product-recommand]',
			  'post_name'      => 'product-recommand',
			  'post_title'     => 'Product Recommand',
			  'post_status'    => 'publish',
			  'post_type'      => 'page',
			  'post_author'    => '0',
			  'ping_status'    => 'close',
			  'post_parent'    => '0',
			  'menu_order'     => '0',
			  'to_ping'        => '',
			  'pinged'         => '',
			  'post_password'  => '',
			  'comment_status' => 'closed',
			  'post_category'  => '',
			  'page_template'  => 'edd-recommand.php'
			);
			$recommand=wp_insert_post( $post );
			update_post_meta($recommand, '_wp_page_template', 'edd-recommand.php');
			$list_collabs[]=$recommand;
		
		}
		update_option('collab_exclude_pages',$list_collabs);
		/* End Alert */
       //add_role( 'team_members', 'Team Members', array( 'read' => true, 'level_0' => true ) );
  		add_option('max_quota','20MB');
		add_option('email_server','http://USER_NAME:PASSWORD@DOMAIN.COM:2082/frontend/x3/mail/doaddpop.html');
		add_option('max_upload_size','2MB');
		add_option('allowed_files_type','jpg|png');
		add_option('maxtime_to_accept_invitation','75Hours');
		add_option('character_limit_for_message','2000');
		add_option('allowed_space_in_gb','1GB');
		add_option('file_limitation_to_upload','20');
		add_option('workroom_email_imap_server','{imap.gmail.com:993/imap/ssl}INBOX');
		add_option('unfinished_item_types','Compose,Recording,Finishing');	
	}
	function Uninstall(){
		delete_option('max_quota');
		delete_option('email_server');
		delete_option('max_upload_size');
		delete_option('allowed_files_type');
		delete_option('maxtime_to_accept_invitation');
		delete_option('character_limit_for_message');
		delete_option('allowed_space_in_gb');
		delete_option('file_limitation_to_upload');
		delete_option('workroom_email_imap_server');
		delete_option('unfinished_item_types');
		//delete_option('collab_exclude_pages');
	}

}