<?php
/**
 * Plugin Name:         Easy Digital Downloads - Collaboration
 * Author:              Daniel 
 * Author URI:          http://www.scriptconnect.com
 * Description:         Complete extension for EDD to manage collaboration.
 * @category            Plugin
 * @copyright           Copyright © 2014 Daniel
 * @author              Daniel 
 * @package             Collaboration
 */

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

class EDD_Collaboration{
	private static $instance;
	public static function instance() {
		if ( !isset( self::$instance ) && !( self::$instance instanceof EDD_Collaboration ) ) {
			self::$instance = new EDD_Collaboration;
			self::$instance->define_globals();
			self::$instance->includes();
			//self::$instance->setup();
			
			

			
		}
		return self::$instance;
	}


	public function includes() {
		require_once collaboration_plugin_dir . 'classes/update-edd.php';
		require_once collaboration_plugin_dir . '/classes/class-stage.php';
		$Dash = new Collaboration_Dashboard;
		require_once collaboration_plugin_dir . '/classes/class-templates.php';
		require_once collaboration_plugin_dir . 'classes/collaboration-functions.php';
		

	}
	public function define_globals() {
		$this->title    = __( 'EDD Collaboration', 'collaboration_fes' );
		$this->file     = __FILE__;
		$this->basename = apply_filters( 'collaboration_plugin_basename', plugin_basename( $this->file ) );
		// Plugin Name
		if ( !defined( 'collaboration_plugin_name' ) ) {
			define( 'collaboration_plugin_name', 'EDD Collaboration' );
		}
		// Plugin Version
		if ( !defined( 'collaboration_plugin_version' ) ) {
			define( 'collaboration_plugin_version', '2.2.9.4' );
		}
		// Plugin Root File
		if ( !defined( 'collaboration_plugin_file' ) ) {
			define( 'collaboration_plugin_file', __FILE__ );
		}
		// Plugin Folder Path
		if ( !defined( 'collaboration_plugin_dir' ) ) {
			define( 'collaboration_plugin_dir', WP_PLUGIN_DIR . '/' . basename( dirname( __FILE__ ) ) . '/' );
		}
		// Plugin Folder URL
		if ( !defined( 'collaboration_plugin_url' ) ) {
			define( 'collaboration_plugin_url', plugin_dir_url( collaboration_plugin_file ) );
		}
		// Plugin Assets URL
		if ( !defined( 'collaboration_assets_url' ) ) {
			define( 'collaboration_assets_url', collaboration_plugin_url . 'assets/' );
		}
	}

}



add_action( 'admin_menu', 'edd_collaboration_admin_menus' );

function edd_collaboration_admin_menus(){
	add_menu_page( 'Collaboration', 'Collaboration', 'manage_options', 'collaboration', 'collab_workrooms',collaboration_plugin_url.'assets/images/favicon.png', 6 );
	
	add_submenu_page( 'collaboration', 'Workrooms', 'Workrooms', 'manage_options', 'collaboration','collab_workrooms');
	add_submenu_page( 'options-writing.php', 'Team Members', 'Team Members', 'manage_options', 'collab_teams','collab_teams');
	add_submenu_page( 'options-writing.php', 'Message', 'Message', 'manage_options', 'collab_message','collab_message');
	
	add_submenu_page( 'options-writing.php', 'Files', 'Files', 'manage_options', 'collab_files','collab_files');
	add_submenu_page( 'options-writing.php', 'Commission', 'Commission', 'manage_options', 'collab_commission','collab_commission');
	
	
	add_submenu_page( 'collaboration', 'Settings', 'Settings', 'manage_options', 'collaboration_settings','collaboration_settings');
	add_submenu_page( 'collaboration', 'Reported Problems', 'Reported Problems', 'manage_options', 'collaboration_problem','collaboration_problem');
	add_submenu_page( 'collaboradtion', 'Problem Details', 'Problem Details', 'manage_options', 'collab_details','collab_details');
}
function collab_teams(){
	require_once collaboration_plugin_dir . '/templates/admin/teams.php';
}
function collab_message(){
	require_once collaboration_plugin_dir . '/templates/admin/message.php';
}
function collab_details(){
	require_once collaboration_plugin_dir . '/templates/admin/problem-details.php';
}

function collab_commission(){
	require_once collaboration_plugin_dir . '/templates/admin/commission.php';
}
function collab_workrooms(){
	require_once collaboration_plugin_dir . '/templates/admin/workrooms.php';
}
function collaboration_problem(){
	require_once collaboration_plugin_dir . '/templates/admin/reported-problem.php';
}
function collab_files(){
   require_once collaboration_plugin_dir . '/templates/admin/files.php';
}
function collaboration_settings(){
   require_once collaboration_plugin_dir . '/templates/admin/settings.php';
}
add_filter('init','flushRules'); 
add_action( 'init', 'add_EDD_pages_rules' ); 
add_filter( 'query_vars', 'setup_Edd_vars' );

function add_EDD_pages_rules() {  
     		add_rewrite_rule('collaboration/workroom/message/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/message/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/files/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/files/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/team-members/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/team-members/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/team-members-invitation/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/team-members-invitation/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/add-workroom/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/add-workroom/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/workroom-alerts/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/workroom-alerts/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/team-commission/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/team-commission/&workroom_id=$matches[1]',
				'top'
			);
			add_rewrite_rule('collaboration/workroom/report-problem/([^/]+)/?',
				'index.php?pagename=collaboration/workroom/report-problem/&workroom_id=$matches[1]',
				'top'
			);
			flush_rewrite_rules(true);
}  
function setup_Edd_vars($query_vars){ 
	array_push($query_vars, 'workroom_id');
	return $query_vars;
}
function flushRules(){
    global $wp_rewrite;
  $wp_rewrite->flush_rules();
}



add_action('List_Of_Collaborators', 'get_list_of_collaborators');
add_filter('Collaboration_Setup_Commission','get_collaboration_setup_commission',10,1);
function EDD_Collaboration() {
	return EDD_Collaboration::instance();
}

EDD_Collaboration();

function Collaboration_Install() {
    require_once collaboration_plugin_dir . '/classes/class-install.php';
    $install = new Collaboration_Install;
    $install->init();
    do_action( 'collaboration_install_actions' );
}
function Collaboration_Uninstall(){
	 require_once collaboration_plugin_dir . '/classes/class-install.php';
    $install = new Collaboration_Install;
    $install->Uninstall();
    do_action( 'collaboration_install_actions' );
}
add_action('init', 'collaboration_frontend_styles');
function collaboration_frontend_styles() {

		/* Admin Core Stylesheet */
		wp_register_style('collaboration_css', collaboration_plugin_url . '/assets/css/sacollab.css');
        wp_enqueue_style('collaboration_css');
		wp_register_style('table_css', collaboration_plugin_url . '/assets/css/jquery.dataTables.css');
        wp_enqueue_style('table_css');
		wp_register_style('table_csfds', collaboration_plugin_url . '/assets/css/jquery.fancybox-1.3.4.css');
        wp_enqueue_style('table_csfds');
		
}

add_action('init', 'collaboration_frontend_scripts');
function collaboration_frontend_scripts() {
	
    wp_enqueue_script(
      'collaboration-script', collaboration_plugin_url . '/assets/js/collaboration.js', 
        array('jquery'),
		'2.3',true 
    );
	 wp_enqueue_script(
      'table-script', collaboration_plugin_url . '/assets/js/jquery.dataTables.min.js', 
        array('jquery'),
		'2.3',false 
    );
	 wp_enqueue_script(
      'table-scridfpt', collaboration_plugin_url . '/assets/js/easyzoom.js', 
        array('jquery'),
		'2.3',false 
    );
	wp_enqueue_script(
      'table-scrrweridfpt', collaboration_plugin_url . '/assets/js/jquery.fancybox-1.3.4.pack.js', 
        array('jquery'),
		'2.3',false 
    );
}
add_action( 'wp_enqueue_scripts', 'collaboration_frontend_scripts' );

function collaboration_head_javascript() {
	echo "<script type='text/javascript'> var Edd={}; Edd.PLUGIN_URL='".collaboration_plugin_url."'; Edd.options={}; </script>";
}
add_action('wp_head', 'collaboration_head_javascript');
add_action('admin_head', 'collaboration_head_javascript');

register_activation_hook( __FILE__, 'Collaboration_Install' );
register_deactivation_hook( __FILE__, 'Collaboration_Uninstall' );
add_action( 'admin_action_delete_message', 'delete_message' );

function delete_message() {
 if(is_array($_POST['message'])){
			 foreach($_POST['message'] as $array){
				wp_delete_post($array);
			 }
		 }
wp_redirect( $_SERVER['HTTP_REFERER'] );
exit(); 
}

add_action( 'admin_action_delete_files', 'delete_files' );

function delete_files() {
 if(is_array($_POST['file'])){
			 foreach($_POST['file'] as $array){
				wp_delete_post($array);
			 }
		 }
wp_redirect( $_SERVER['HTTP_REFERER'] );
exit(); 
}
add_filter( 'wp_mail_content_type', 'set_html_content_type' );
function set_html_content_type() {

	return 'text/html';
}
add_action( 'admin_action_delete_workroom', 'delete_workroom' );

function delete_workroom() {
if(!isset($_POST['filter_button']) && is_array($_POST['workroom'])){
			 foreach($_POST['workroom'] as $Workroom_Id){
				wp_delete_post($Workroom_Id);
				delete_post_meta_by_post_id($Workroom_Id);
				$files=get_files($Workroom_Id);
				foreach($files as $file){
					wp_delete_post($file->ID);	
					delete_post_meta_by_post_id($file->ID);
				}
				$messages=get_messages($Workroom_Id);
				foreach($messages as $message){
					wp_delete_post($message->ID);	
					delete_post_meta_by_post_id($message->ID);
				}
			 }
		 }
wp_redirect( $_SERVER['HTTP_REFERER'] );
exit(); 
}


add_action( 'admin_init', 'admin_started' );
function admin_started(){
	if(isset($_POST['filter_button'])){
		if($_POST['filter']=='-1')$_POST['filter']='all';
		set_transient('Filter_By',admin_filter_type($_POST['filter']), 600);
		wp_redirect($_POST['_wp_http_referer'] );
		exit;
	}
	if(isset($_POST['page']) && ($_POST['page']=='collaboration_problem') && isset($_POST['problem']) && is_array($_POST['problem'])){
			foreach($_POST['problem'] as $Workroom_Id){
				wp_delete_post($Workroom_Id);
				delete_post_meta_by_post_id($Workroom_Id);
					$files=get_files($Workroom_Id);
					foreach($files as $file){
						wp_delete_post($file->ID);	
						delete_post_meta_by_post_id($file->ID);
					}
					$messages=get_messages($Workroom_Id);
					foreach($messages as $message){
						wp_delete_post($message->ID);	
						delete_post_meta_by_post_id($message->ID);
					}
			}
		wp_redirect( $_SERVER['HTTP_REFERER'] );
		exit();
	}
}
add_action("wp_ajax_Accept_Invitation", "accept_invitation");
add_action("wp_ajax_nopriv_Accept_Invitation", "accept_invitation");
add_action("fes_submission_preparing", "check_product_status_before_save");
add_action("Add_Collaborators_Regular_Unfinished_Commission", "set_collaborators_regular_unfinished_commission");
add_action("wp_ajax_check_emails", "check_emails");
add_action("wp_ajax_nopriv_check_emails", "check_emails");
function accept_invitation(){
	if ( !wp_verify_nonce($_REQUEST['accept_invitation_nonce'], "accept_invitation_nonce")) {
   } 
	$Workroom_Id=$_GET['Workroom_Id'];
	$Action='Accepted';
	$Collaborator_Id=$_GET['Collaborator_Id'];
	$Alert_Id=$_GET['Alert_Id'];
	$Create_Invitation_Status=get_post_meta($Workroom_Id,'Invitation_Status',true);
	if(!is_array($Create_Invitation_Status)){
			$Create_Invitation_Status=array();
	}
	$size=sizeof($Create_Invitation_Status);
	$i=0;
	$What_Do="";
	$Invited_Data='';
	
	foreach($Create_Invitation_Status as $value){
		$i++;
		if(($value['Alert_Id']==$Alert_Id) && ($value['Collaborator_Id']==$Collaborator_Id)){
			$What_Do="Accept";
			$Invited_Data=$value['Invited_Data'];
			break;
		}
	}
	$ExpiryDate=(int)get_option('maxtime_to_accept_invitation');
	$t1 =strtotime($Invited_Data);
	$t2 =strtotime(date("Y-m-d H:i:s"));
	$diff = $t2 - $t1;
	$hours = $diff / ( 60 * 60 );
	$Inv=new stdClass;
	if($ExpiryDate>=$hours){
		$Inv->Status='Accepted';
		update_post_meta($Alert_Id,'Is_Alert_Read','YES');
		update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id);	
	}else{
		$Inv->Status='Expired';	
	}

	$Inv->Collaborator_Name=get_the_author_meta('display_name', $Collaborator_Id);
	$Inv->Workroom_Name=get_the_title($Workroom_Id);
	$Inv->Site_Link=get_site_url();
	$Inv->Site_Name=get_bloginfo('name');
	ob_start();
	include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-invitation-accept-template.php');
	$Accept_Invitation=ob_get_clean();
	echo $Accept_Invitation;
	die();
}


