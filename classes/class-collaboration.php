<?php
require_once('../../../../wp-load.php');
if(isset($_GET['Method']) && $_GET['Method']!=''){
	$_POST['Method']=$_GET['Method'];
}

$newa=new AjaxPost();
if(isset($_POST['Method']) && $_POST['Method']!=''){
$_POST['method']=$_POST['Method'];
}
if(isset($_POST['method']) && $_POST['method']!='' && method_exists($newa,$_POST['method'])){
	
	$newa->$_POST['method']();
}else{
	echo "Invalid Request";
}
class AjaxPost{
	public $posts='';
	function AjaxPost(){
			if(isset($_POST['metas'])){
				parse_str($_POST['metas'],$posts);
				$this->posts=$posts;
			}
	}
	function Save_Message(){
		
		$current_user = wp_get_current_user();
		
		$email_name = $current_user->user_firstname .' ' .$current_user->user_lastname . ' - '.$current_user->user_email;
		$date=date('Y-m-d H:i:s');
		$message=$this->posts['POST_MESSAGE'];
		$post = array(
		  'post_content'   =>$message,
		  'post_name'      => $email_name,
		  'post_title'     => $email_name,
		  'post_status'    =>'publish',
		  'post_type'      => 'collab-msg',
		  'post_author'    => get_current_user_id(),
		  'ping_status'    =>'close',
		  'post_parent'    => '0',
		  'menu_order'     =>'0',
		  'to_ping'        =>'',
		  'pinged'         => '',
		  'post_password'  =>'',
		  'post_date'      =>$date,
		  'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $post );
		if(isset($this->posts['attachments']) && is_array($this->posts['attachments'])){
			update_post_meta($page_id, '_wp_collaboration_attachments',$this->posts['attachments']);
		}
		update_post_meta($page_id, 'Workroom_Id',$this->posts['Workroom_Id']);
		//Generate Alert Id
		$Alert_Id=uniqid();
		//End Alert Id
		update_post_meta($page_id,'Edd_Alert_Id',$Alert_Id);
		$son=array(
			"name"=>$current_user->display_name,
			"message"=>nl2br($message),
			"attachments"=>get_post_meta($page_id, '_wp_collaboration_attachments',true),
			"date"=>date_format(date_create($date),'Y/m/d'),
			"time"=>date_format(date_create($date),'H:i A'),
			"Workroom_Id"=>$this->posts['Workroom_Id'],
			"Artist_Url"=>get_permalink(get_page_by_path('user-details')).'?id='.get_current_user_id()
		);
		//Add Notifications and Email
		$author_id=get_current_user_id();
		$Workroom_Id=$this->posts['Workroom_Id'];
		
			
			$Teams=get_teams_as_workroom($Workroom_Id);
			foreach($Teams as $team){
				$Alert_Title=''.get_the_author_meta('display_name', $author_id).' posted a message in '.get_the_title($Workroom_Id).identity(array('Workroom_Id'=>$Workroom_Id,'Type'=>'Message','Collaborator_Id'=>$team->Collaborator_Id));
				if($team->Collaborator_Id==$author_id)continue 1;
				$MessageAlert=new stdClass();
				$MessageAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
				$MessageAlert->Workroom_Name=get_the_title($Workroom_Id);
				$MessageAlert->Notification_Link=$this->Get_Message_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
				$MessageAlert->Notification_From=get_the_author_meta('display_name', $author_id);
				$MessageAlert->Workroom_Message=nl2br($message);
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-message-post-template.php');
				$Get_Template=ob_get_clean();
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
				$css=ob_get_clean();
				//Add Alert
				$collection=array(
					'Edd_Alert_Message'=>$Get_Template,
					'Edd_Alert_Title'=>$Alert_Title,
					'Edd_Alert_Id'=>$Alert_Id,
					'Edd_Alert_To'=>$team->Collaborator_Id,
					'Edd_Alert_From'=>$author_id,
					'Edd_Alert_Message_Url'=>$this->Get_Message_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
				);
				edd_add_alerts($collection);
				//End Add Alerts
				wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
				wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
				
				
			}
		//End Adding notifications and emails
		echo json_encode($son);
	}
	
	function Save_Report_Message(){
		$current_user = wp_get_current_user();
		$email_name = $current_user->user_firstname .' ' .$current_user->user_lastname . ' - '.$current_user->user_email;
		$date=date('Y-m-d H:i:s');
		$message=$this->posts['POST_MESSAGE'];
		$post = array(
		  'post_content'   =>$message,
		  'post_name'      => $email_name,
		  'post_title'     => $email_name,
		  'post_status'    =>'publish',
		  'post_type'      =>'collab-msg-rep',
		  'post_author'    =>get_current_user_id(),
		  'ping_status'    =>'close',
		  'post_parent'    => '0',
		  'menu_order'     =>'0',
		  'to_ping'        =>'',
		  'pinged'         => '',
		  'post_password'  =>'',
		  'post_date'      =>$date,
		  'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $post );
		if(isset($this->posts['attachments']) && is_array($this->posts['attachments'])){
			update_post_meta($page_id, '_wp_collaboration_attachments',$this->posts['attachments']);
		}
		update_post_meta($page_id, 'Report_Id',$this->posts['Report_Id']);
		/* send Emails */
		$Alert_Title=get_the_author_meta('display_name',get_current_user_id()).' sent  you a message '.identity(array('Workroom_Id'=>$this->posts['Report_Id'],'Type'=>'Report','Collaborator_Id'=>get_post_field('post_author',$this->posts['Report_Id'])));
		$ReportAlert=new stdClass();
		$ReportAlert->Collaborator_Name=get_the_author_meta('display_name',get_post_field('post_author',$this->posts['Report_Id']));
		$ReportAlert->Notification_From=get_the_author_meta('display_name',get_current_user_id());
		$ReportAlert->Message=nl2br($message);
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-report-a-problem-template.php');
		$Get_Template=ob_get_clean();
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
		$css=ob_get_clean();
		//Add Alert
	
		//End Add Alerts
		wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
		wp_mail(get_the_author_meta('user_email',$this->posts['Report_Id']), $Alert_Title,$css.$Get_Template);
		
		/* End send email */
		
		
		//End Adding notifications and emails
		echo json_encode($son);
	}
	function Get_Message_Notification_Link($Workroom_Id,$Collaborator_Id,$Alert_Id,$Anchor=true){
		if($Anchor){
			return "<a href='".get_permalink(get_page_by_path('collaboration/workroom/message')).$Workroom_Id."/?Alert_Id=".$Alert_Id."'>Click Here</a>";
		}else{
			return get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$Workroom_Id."/?Alert_Id=".$Alert_Id;
		}
	}
	function Save_Files(){
		$current_user =get_current_user_id();
		
		$email_name =get_the_author_meta('display_name', get_current_user_id()); // $current_user->user_firstname .' ' .$current_user->user_lastname;
		$date=date('Y-m-d H:i:s');
		$post = array(
		  'post_content'   => 'Edd Files',
		  'post_name'      => $email_name,
		  'post_title'     => $_POST['name'],
		  'post_status'    =>'publish',
		  'post_type'      =>'collab-files',
		  'post_author'    => get_current_user_id(),
		  'ping_status'    =>'close',
		  'post_parent'    => '0',
		  'menu_order'     =>'0',
		  'to_ping'        =>'',
		  'pinged'         => '',
		  'post_password'  =>'',
		  'post_date'      =>$date,
		  'comment_status' => 'closed',
		);
		$page_id   = wp_insert_post( $post );
		update_post_meta($page_id, '_wp_collaboration_file_size',$_POST['size']);
		update_post_meta($page_id, 'Workroom_Id',$_POST['Workroom_Id']);
		update_post_meta($page_id, 'Item_Id',$_POST['Item_Id']);
		//Generate Alert Id
		$Alert_Id=uniqid();
		//End Alert Id
		update_post_meta($page_id,'Edd_Alert_Id',$Alert_Id);
		//Add Notifications and Email
		$author_id=get_current_user_id();
		$Workroom_Id=$_POST['Workroom_Id'];
		$Alert_Title=''.get_the_author_meta('display_name', $author_id).' posted a file in '.get_the_title($Workroom_Id);
			$Teams=get_teams_as_workroom($Workroom_Id);
			foreach($Teams as $team){
				if($team->Collaborator_Id==$author_id)continue 1;
				$FileAlert=new stdClass();
				$FileAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
				$FileAlert->Workroom_Name=get_the_title($Workroom_Id);
				$FileAlert->Notification_Link=$this->Get_File_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
				$FileAlert->Notification_From=get_the_author_meta('display_name', $author_id);
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-file-post-template.php');
				$Get_Template=ob_get_clean();
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
				$css=ob_get_clean();
				//Add Alert
				$collection=array(
					'Edd_Alert_Message'=>$Get_Template,
					'Edd_Alert_Title'=>$Alert_Title,
					'Edd_Alert_Id'=>$Alert_Id,
					'Edd_Alert_To'=>$team->Collaborator_Id,
					'Edd_Alert_From'=>$author_id,
					'Edd_Alert_Message_Url'=>$this->Get_File_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
				);
				edd_add_alerts($collection);
				//End Add Alerts
				wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
				wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
			}
		//End Adding notifications and emails
		
		
		// Stop notificatuion 
		$son=array(
			"name"=>$_POST['name'],
			"size"=>$_POST['size'],
			"date"=>date_format(date_create($date),'M d, Y - H:i A'),
			"author"=>$email_name,
			"File_Id"=>$page_id,
			"Detail"=>get_workroom_space_details($_POST['Workroom_Id'])
		);
		echo json_encode($son);
	}
	function Get_File_Notification_Link($Workroom_Id,$Collaborator_Id,$Alert_Id,$Anchor=true){
		if($Anchor){
			return "<a href='".get_permalink(get_page_by_path('collaboration/workroom/files')).$Workroom_Id."/?Alert_Id=".$Alert_Id."'>Click Here</a>";
		}else{
			return get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$Workroom_Id."/?Alert_Id=".$Alert_Id;
		}
	}
	function Delete_File(){
		$File_Id=$_POST['File_Id'];
		wp_delete_post($File_Id);
		$Workroom_Id=get_post_meta($File_Id,'Workroom_Id',true);
		$status=array('Status'=>'success','Message'=>'File is Successfully Deleted.','Color'=>'green','File_Id'=>$File_Id,"Detail"=>get_workroom_space_details($Workroom_Id));
		echo json_encode($status);
					
	}
	
	function Download_File(){
		if(isset($_GET['File_Name']) && file_exists(collaboration_plugin_dir.'files/files/'.$_GET['File_Name'])){
			$file =collaboration_plugin_dir.'files/files/'.$_GET['File_Name'];
			header('Content-Description: File Transfer');
			header("Content-type: image/jpg");
			header("Content-disposition: attachment; filename= ".$_GET['File_Name']."");
			readfile($file)	;
			die();
			
		}
	}
	function Download_Attachments(){
		if(isset($_GET['File_Name']) && file_exists(collaboration_plugin_dir.'attachments/files/'.$_GET['File_Name'])){
			$file =collaboration_plugin_dir.'attachments/files/'.$_GET['File_Name'];
			header('Content-Description: File Transfer');
			header("Content-type: image/jpg");
			header("Content-disposition: attachment; filename= ".$_GET['File_Name']."");
			readfile($file)	;
			die();
			
		}
	}
	function Update_Workroom(){
				$ITEM_ID=!isset($this->posts['ITEM_ID'])?'0':$this->posts['ITEM_ID'];
				$USER_ID=!isset($this->posts['USER_ID'])?'0':$this->posts['USER_ID'];
				if ( !isset($this->posts[ 'workroom_name' ]) || $this->posts[ 'workroom_name' ] === '' ){
					$this->set_error( __( 'Please fill out workroom name!', 'edd_collaboration' ) );
				}
				if($ITEM_ID==0){
				if ( !isset($this->posts[ 'Unfinished_Item_ID' ]) || $this->posts[ 'Unfinished_Item_ID' ] === '' ){
					$this->set_error( __( 'Please select a unfinished Item', 'edd_collaboration' ) );
				}
				}
				$post = array(
				  'post_content'   =>$this->posts[ 'workroom_description' ],
				  'post_name'      => 'workroom'.time(),
				  'post_title'     =>$this->posts[ 'workroom_name' ],
				  'post_status'    =>'publish',
				  'post_type'      => 'edd_workroom',
				  'post_author'    =>$USER_ID,
				  'ping_status'    =>'close',
				  'post_parent'    =>'0',
				  'menu_order'     =>'0',
				  'to_ping'        =>'',
				  'pinged'         => '',
				  'post_password'  =>'',
				  'comment_status' => 'closed',
				  'post_category'  =>'',
				);
				if($ITEM_ID==0){
					$Workroom_Email_Address=uniqid();
					if(!create_workroom_email_account($Workroom_Email_Address))$this->set_error('There is problem while creating workroom - Please try again later.');
					$user_id=wp_insert_post($post);
					if(is_wp_error( $user_id ) ) {
						$status=array('Status'=>'error','Message'=>$user_id->get_error_message(),'Color'=>'red','URL'=>'stay');
						echo json_encode($status);
						die();
					}
					update_post_meta($user_id,'Unfinished_Item_ID',$this->posts[ 'Unfinished_Item_ID' ]);
					update_post_meta($user_id,'Invitation_Status_'.$USER_ID,'Accepted');
					update_post_meta($user_id,'Workroom_Email_Address',$Workroom_Email_Address);
					$status=get_post_meta($user_id,'Collaboration_Status',true);
					
					if($status==''){
						update_post_meta($user_id,'Collaboration_Status','in-progress');
					}
					$status=array('Status'=>'success','Message'=>'Workroom is Successfully Created.','Color'=>'green','URL'=>get_permalink(get_page_by_path('collaboration')));
					$this->Invite_Main_Collaborators($user_id);
					echo json_encode($status);
				}else{
					$post['ID']=$ITEM_ID;
					$user_id = wp_update_post($post );
					update_post_meta($user_id, 'Unfinished_Item_ID',$this->posts[ 'Unfinished_Item_ID' ]);
					if(is_wp_error( $user_id ) ) {
						$status=array('Status'=>'error','Message'=>$user_id->get_error_message(),'Color'=>'red','URL'=>'stay');
						echo json_encode($status);
						die();
					}
					$status=array('Status'=>'success','Message'=>'Workroom is Successfully updated.','Color'=>'green','URL'=>get_permalink(get_page_by_path('collaboration')));
					echo json_encode($status);
				}
				
	}
	function Delete_Workroom(){

		$Workroom_Id=$_POST['Workroom_Id'];
		$Workroom=wp_delete_post($Workroom_Id);
		delete_post_meta_by_post_id($Workroom_Id);
		if(is_wp_error($Workroom)){
			$status=array('Status'=>'error','Message'=>$user_id->get_error_message(),'Color'=>'red','URL'=>'stay');
			echo json_encode($status);
			die();
		}
		delete_post_meta_by_post_id($Workroom_Id);
		$files=get_messages($Workroom_Id);
		foreach($files as $file){
			wp_delete_post($file->ID);	
			delete_post_meta_by_post_id($file->ID);
		}
		$messages=get_messages($Workroom_Id);
		foreach($messages as $message){
			wp_delete_post($message->ID);	
			delete_post_meta_by_post_id($message->ID);
		}
		$status=array('Status'=>'success','Workroom_Id'=>$Workroom_Id,'Message'=>'Workroom is Successfully deleted.','Color'=>'green','URL'=>get_permalink(get_page_by_path('collaboration')));
		echo json_encode($status);
	}
	function Invite_Main_Collaborators($workroom_id=0){
		$Item_ID=get_item_id_by_workroom_id($workroom_id);//get_post_meta($workroom_id,'Unfinished_Item_ID',true);
		$author_id=get_post_field('post_author',$workroom_id);
		$data=get_list_of_parent_items($Item_ID,array());
		$Alert_Title='You have received Invitation From '.get_the_author_meta('display_name', $author_id).' to join in his workroom';
		foreach($data as $vals){
			$to_author_id=get_post_field('post_author',$vals);
			//Generate Unique Id
			$Alert_Id=uniqid();
			//End Generate Unique ID
			
			
			
			$InvAlerts=new stdClass();
			$InvAlerts->Collaborator_Name=' '.get_the_author_meta('first_name', $to_author_id).' '.get_the_author_meta('last_name', $to_author_id);
			$InvAlerts->Workroom_Name=get_the_title($workroom_id);
			$InvAlerts->Invitation_Accept_Link=$this->Get_Invitation_Accept_Link($workroom_id,$to_author_id,$Alert_Id);
			$InvAlerts->Invitation_Sender=get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id);
			ob_start();
			include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-invitation-email-template.php');
			$Get_Template=ob_get_clean();
			ob_start();
			include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
			$css=ob_get_clean();
			//Add Alert
			$collection=array(
				'Edd_Alert_Message'=>$Get_Template,
				'Edd_Alert_Title'=>$Alert_Title,
				'Edd_Alert_Id'=>$Alert_Id,
				'Edd_Alert_To'=>$to_author_id,
				'Edd_Alert_From'=>$author_id,
				'Edd_Alert_Message_Url'=>$this->Get_Invitation_Accept_Link($workroom_id,$to_author_id,$Alert_Id,false)
			);
			$this->update_collaborators_invitation($workroom_id,'Invited',$to_author_id,$Alert_Id);
			edd_add_alerts($collection);
			//End ALert
			wp_mail('rajiya5@yahoo.com', $Alert_Title,$css.$Get_Template);
			wp_mail(get_the_author_meta('user_email',$to_author_id), $Alert_Title,$css.$Get_Template);
		}
	}
	
	function Update_User(){
		$USER_ID=!isset($this->posts['ID'])?'0':$this->posts['ID'];
		$userdata = array();
				if ( !isset($this->posts[ 'first_name' ]) || $this->posts[ 'first_name' ] === '' ){
					$this->set_error( __( 'Please fill out the registration form!', 'edd_collaboration' ) );
				}
				else{
					$userdata[ 'first_name' ] = sanitize_text_field( $this->posts[ 'first_name' ] );
				}
				if ( !isset($this->posts[ 'last_name' ]) || $this->posts[ 'last_name' ] === '' ){
					$this->set_error( __( 'Please fill out the registration form!', 'edd_collaboration' ) );
				}
				else{
					$userdata[ 'last_name' ] = sanitize_text_field( $this->posts[ 'last_name' ] );
				}
				if ( !isset($this->posts[ 'user_email' ]) || $this->posts[ 'user_email' ] === '' ){
					$this->set_error( __( 'Please fill out the registration form!', 'edd_collaboration' ) );
				}
				if ( !is_email( $this->posts[ 'user_email' ] ) ){
					$this->set_error( __( 'Please enter a valid email!', 'edd_collaboration' ) );
				}
				else{
					$userdata[ 'user_email' ] = sanitize_email( $this->posts[ 'user_email' ] );
				}
				$this->posts[ 'user_login' ]=$this->posts[ 'user_email' ];
				if ( !isset($this->posts[ 'user_login' ]) || $this->posts[ 'user_login' ] === '' ){
					$this->set_error( __( 'Please fill out the registration form!', 'edd_collaboration' ) );
				}
				else{
					$userdata[ 'user_login' ] = sanitize_user( $this->posts[ 'user_login' ] );
				}
				if ( !isset($this->posts[ 'display_name' ]) || $this->posts[ 'display_name' ] === '' ){
					$this->set_error( __( 'Please fill out the registration form!', 'edd_collaboration' ) );
				}
				else{
					$userdata[ 'display_name' ] = sanitize_text_field( $this->posts[ 'display_name' ] );
				}
				$this->posts[ 'user_url' ]='';
				if ( isset( $this->posts[ 'user_url' ] ) ){
					$userdata[ 'user_url' ] = sanitize_text_field( $this->posts[ 'user_url' ] );
				}
				if ( isset( $this->posts[ 'description' ] ) ){
					$userdata[ 'description' ] = $this->posts[ 'description' ];
				}
				/*
				$pass_element    = current( $pass_element );
				$password        = ( isset( $this->posts[ 'pass1' ] ) ? sanitize_text_field( $this->posts[ 'pass1' ] ) : '' );
				$password_repeat = ( isset( $this->posts[ 'pass2' ] ) ? sanitize_text_field( $this->posts[ 'pass2' ] ) : '' );
				// check only if it's filled
				if ( isset( $this->posts[ 'pass2' ] ) && ( $password != $password_repeat ) ) {
					$this->signal_error( __( 'Password didn\'t match', 'edd_fes' ) );
				}
				*/
				// password is good
				$userdata[ 'user_pass' ] = 'TEST';
				$userdata['team_members']=get_current_user_id();
				// see if an account? If so log in
				$userdata[ 'role' ] = 'team_members';
				$userdata[ 'user_registered' ] = date( 'Y-m-d H:i:s' );
				if($USER_ID==0){
					$user_id = wp_insert_user( $userdata );
					if(is_wp_error( $user_id ) ) {
						$status=array('Status'=>'error','Message'=>$user_id->get_error_message(),'Color'=>'red');
						echo json_encode($status);
						die();
					}
					$status=array('Status'=>'success','Message'=>'Team Details is Successfully Saved.','Color'=>'green');
					echo json_encode($status);
				}else{
					$userdata['ID']=$USER_ID;
					$user_id = wp_update_user( $userdata );
					foreach($userdata as $key=>$val){
					update_user_meta($USER_ID,$key,$val);
					}
					if(is_wp_error( $user_id ) ) {
						$status=array('Status'=>'error','Message'=>$user_id->get_error_message(),'Color'=>'red');
						echo json_encode($status);
						die();
					}
					$status=array('Status'=>'success','Message'=>'Team Details is Successfully updated.','Color'=>'green');
					echo json_encode($status);
				}
	}
	function Update_Commission(){
		$item_id=$_POST['Workroom_Id']; //get_main_item_id_by_workroom_id($_POST['Workroom_Id']);
		if(get_post_meta($item_id,'Final_Approved',true)!='YES'){
		update_user_meta($_POST['Author_Id'],'download-'.$item_id,$_POST['Commission_Amount'],false);
		
		//========================================================================
		
		//Generate Alert Id
		$Alert_Id=uniqid();
		//End Alert Id
		//update_post_meta($page_id,'Edd_Alert_Id',$Alert_Id);
		//Add Notifications and Email
		$author_id=get_current_user_id();
		$Workroom_Id=$_POST['Workroom_Id'];
		$Alert_Title=''.get_the_author_meta('display_name', $author_id).' Updated Commission in '.get_the_title($Workroom_Id);
			$Teams=get_teams_as_workroom($Workroom_Id);
			foreach($Teams as $team){
				if($team->Collaborator_Id==$author_id)continue 1;
				$CommissionAlert=new stdClass();
				$CommissionAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
				$CommissionAlert->Workroom_Name=get_the_title($Workroom_Id);
				
				//---------------------
				$CommissionAlert->Notification_Link=$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
				$CommissionAlert->Notification_From=get_the_author_meta('display_name', $author_id);
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-update-commission-template.php');
				$Get_Template=ob_get_clean();
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
				$css=ob_get_clean();
				//Add Alert
				$collection=array(
					'Edd_Alert_Message'=>$Get_Template,
					'Edd_Alert_Title'=>$Alert_Title,
					'Edd_Alert_Id'=>$Alert_Id,
					'Edd_Alert_To'=>$team->Collaborator_Id,
					'Edd_Alert_From'=>$author_id,
					'Edd_Alert_Message_Url'=>$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
				);
				edd_add_alerts($collection);
				//End Add Alerts
				
				
				wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
				wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
			}
		//End Adding notifications and emails
		//======================================================================================
		echo json_encode(array('Status_Message'=>'You\'ve successfully updated your commission.','Show'=>show_checkboxes($Workroom_Id)));
		}else{
		echo json_encode(array('Status_Message'=>'You cant edit it.','Show'=>false));	
		}
	}
	function Get_Update_Notification_Link($Workroom_Id,$Collaborator_Id,$Alert_Id,$Anchor=true){
		if($Anchor){
			return "<a href='".get_permalink(get_page_by_path('collaboration/workroom/team-commission')).$Workroom_Id."/?Alert_Id=".$Alert_Id."'>Click Here</a>";
		}else{
			return get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$Workroom_Id."/?Alert_Id=".$Alert_Id;
		}
	}
	function Approve_User_Commission(){
		$item_id=$_POST['Workroom_Id']; //get_main_item_id_by_workroom_id($_POST['Workroom_Id']);
		if(get_post_meta($item_id,'Final_Approved',true)!='YES'){
		update_user_meta($_POST['Author_Id'],'download-'.$item_id,$_POST['Commission_Amount'],false);
		update_user_meta($_POST['Author_Id'],'download-'.$item_id.'-approved',$_POST['Commission_Approved'],false);
		//========================================================================
		//Generate Alert Id
		$Alert_Id=uniqid();
		//End Alert Id
		//update_post_meta($page_id,'Edd_Alert_Id',$Alert_Id);
		//Add Notifications and Email
		$author_id=get_current_user_id();
		$Workroom_Id=$_POST['Workroom_Id'];
		$Alert_Title=''.get_the_author_meta('display_name', $author_id).' Approved Commission in '.get_the_title($Workroom_Id);
			$Teams=get_teams_as_workroom($Workroom_Id);
			foreach($Teams as $team){
				if($team->Collaborator_Id==$author_id)continue 1;
				$CommissionAlert=new stdClass();
				$CommissionAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
				$CommissionAlert->Workroom_Name=get_the_title($Workroom_Id);
				
				//---------------------
				$CommissionAlert->Notification_Link=$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
				$CommissionAlert->Notification_From=get_the_author_meta('display_name', $author_id);
				$CommissionAlert->Status=($_POST['Commission_Approved']=='YES')?'Approved':'Unapprived';
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-approved-commission-template.php');
				$Get_Template=ob_get_clean();
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
				$css=ob_get_clean();
				//Add Alert
				$collection=array(
					'Edd_Alert_Message'=>$Get_Template,
					'Edd_Alert_Title'=>$Alert_Title,
					'Edd_Alert_Id'=>$Alert_Id,
					'Edd_Alert_To'=>$team->Collaborator_Id,
					'Edd_Alert_From'=>$author_id,
					'Edd_Alert_Message_Url'=>$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
				);
				edd_add_alerts($collection);
				//End Add Alerts
				
				
				
				wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
				wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
			}
		//End Adding notifications and emails
		//======================================================================================
		if($_POST['Commission_Approved']=='YES'){
			echo json_encode(array('Status_Message'=>'You\'ve successfully approved your commission.'));
		}
		}else{
			echo json_encode(array('Status_Message'=>'You cant edit it.'));
		}
	}
	function Approve_Commission(){
		$Workroom_Id=$_POST['Workroom_Id'];
		$Main_Download_Id=$Workroom_Id;//get_main_item_id_by_workroom_id($Workroom_Id);
		if(get_post_meta($Main_Download_Id,'Final_Approved',true)=='YES'){
			$this->set_error('Commission is already approved.');
		}
		// Check if all accepted user entered commission percentage is equall to 100
		$Unfinished_Queues=get_teams_as_workroom($_POST['Workroom_Id']);
		$Total_Commission_Amount=0;
		foreach($Unfinished_Queues as $Queues_Item){
		  		//$Queues_Author_Id = get_post_field( 'post_author', $Queues_Item );
				
				$Queues_Author_Id=$Queues_Item->Collaborator_Id;
				$status=$Queues_Item->Invitation_Status;
				$Item_Id=$Queues_Item->Item_Id;
				$Main_Item_Id=$Queues_Item->Main_Item_Id;
				if($status=='Accepted'){
					$user_entered_amount=(int) get_user_meta($Queues_Author_Id,'download-'.$Main_Download_Id,true);
					if($user_entered_amount==0) $this->set_error('You can not approve Comession, '.get_the_author_meta('first_name', $Queues_Author_Id).' '.get_the_author_meta('last_name', $Queues_Author_Id).'  have not entered any amount for commission.');
					// Check if he has approved his entered commission.
					$is_approved=get_user_meta($Queues_Author_Id,'download-'.$Main_Download_Id.'-approved',true);
					if($is_approved!='YES')$this->set_error('You can not approve Comession, '.get_the_author_meta('first_name', $Queues_Author_Id).' '.get_the_author_meta('last_name', $Queues_Author_Id).' have not Approved his commission.');
					/*if($Item_Id=='NOT-SOLD'){
						$this->set_error('You can not approve Comession, The item is still not sold as Complete Version. Please ask '.get_the_author_meta('first_name', $Queues_Author_Id).' '.get_the_author_meta('last_name', $Queues_Author_Id).' to repost item with complete version.');
					}*/
					$Total_Commission_Amount=$Total_Commission_Amount+$user_entered_amount;
				}
		}
		if($Total_Commission_Amount==100){
			//Check if last ITEM is sold as Finished.
			$Last_Id=get_last_product_this_item($Main_Download_Id);
			$Collaboration_Product_Status=get_post_meta($Last_Id,'_edd_product_status');
			$status='';
			if(is_array($Collaboration_Product_Status) && $Collaboration_Product_Status[0]!=''){
				$status=$Collaboration_Product_Status[0];
			}
			/*if(!is_finished($status)){
				$this->set_error('The Product is still not sold as Finished. You can only approve Commission if product is Completely sold as Finished.');
			}*/
			update_post_meta($Main_Download_Id,'Final_Approved','YES');
			update_post_meta($Workroom_Id,'Collaboration_Status','complete');
			
			//========================================================================
		//Generate Alert Id
		$Alert_Id=uniqid();
		//End Alert Id
		//update_post_meta($page_id,'Edd_Alert_Id',$Alert_Id);
		//Add Notifications and Email
		$author_id=get_current_user_id();
		$Alert_Title=''.get_the_author_meta('display_name', $author_id).' Approved Final Commission in '.get_the_title($Workroom_Id);
			$Teams=get_teams_as_workroom($Workroom_Id);
			foreach($Teams as $team){
				if($team->Collaborator_Id==$author_id)continue 1;
				$CommissionAlert=new stdClass();
				$CommissionAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
				$CommissionAlert->Workroom_Name=get_the_title($Workroom_Id);
				
				//---------------------
				$CommissionAlert->Notification_Link=$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
				$CommissionAlert->Notification_From=get_the_author_meta('display_name', $author_id);
				$CommissionAlert->Status="Final Approved";
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-approved-commission-template.php');
				$Get_Template=ob_get_clean();
				ob_start();
				include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
				$css=ob_get_clean();
				//Add Alert
				$collection=array(
					'Edd_Alert_Message'=>$Get_Template,
					'Edd_Alert_Title'=>$Alert_Title,
					'Edd_Alert_Id'=>$Alert_Id,
					'Edd_Alert_To'=>$team->Collaborator_Id,
					'Edd_Alert_From'=>$author_id,
					'Edd_Alert_Message_Url'=>$this->Get_Update_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
				);
				edd_add_alerts($collection);
				//End Add Alerts
				wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
				wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
			}
		//End Adding notifications and emails
		//======================================================================================
			
			
			
			
			$url=add_query_arg( array('task'=>'new-product','wid'=>$Workroom_Id),get_permalink(get_page_by_path('fes-vendor-dashboard')));
			$this->set_success('You\'ve successfully Approved commission.  Click <a href="'.$url.'" class="button" id="repost">here</a> to repost product');
		}else{
			$this->set_error('All users entered amount commission persentage is not equall to 100. Please check again and try to approve later.');
		}
	}
	function Update_Collaborators_Table(){
		$Item_Id=$_POST['Workroom_Id'];
		$DataItems=array();
		$rows=get_unfinished_childs($Item_Id);
		if(is_array($rows) && sizeof($rows)>0){
		  foreach($rows as $records){
			 $author_id = get_post_field( 'post_author', $records );
			 if(get_lastest_status($records)=='Accepted') continue 1;
			 if(get_lastest_status($records)=='Invited') continue 1;
			 if($author_id==get_current_user_id($records))continue 1;
			  $DataItems[]=array(
				  'name'=>get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id),
				  'display_name'=>get_the_author_meta('display_name', $author_id),
				  'item_name'=>get_the_title($records),
				  'download_id'=>$records,
				  'workroom_id'=>$Item_Id,
				  'author_id'=>get_current_user_id(),
			  );
		  }
		}
		echo json_encode($DataItems);
	}
	function Edit_Collaborators(){
		$Workroom_Id=$_POST['Workroom_Id'];
		$Action=$_POST['Action'];
		$Collaborator_Id=$_POST['Collaborator_Id'];
		$Alert_Id=$_POST['Alert_Id'];
		$this->update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id);
		$status=array('Collaborator_Id'=>$Collaborator_Id);
		echo json_encode($status);
	}
	function Invitation_Collaborators(){
		$Workroom_Id=$_POST['Workroom_Id'];
		$Action=$_POST['Action'];
		$Collaborator_Id=$_POST['Collaborator_Id'];
		//==============
		if($Action=='Invited'){
			$Item_ID=get_item_id_by_workroom_id($Workroom_Id);//get_post_meta($Workroom_Id,'Unfinished_Item_ID',true);
			$author_id=get_post_field('post_author',$Workroom_Id);
			$to_author_id=$Collaborator_Id;
			$Alert_Title='You have received Invitation From '.get_the_author_meta('display_name', $author_id).' to join in his workroom';
			//Generate Alert Id
			$Alert_Id=uniqid();
			//End Alert Id
			$InvAlerts=new stdClass();
			$InvAlerts->Collaborator_Name=get_the_author_meta('display_name', $to_author_id);
			$InvAlerts->Workroom_Name=get_the_title($Workroom_Id);
			$InvAlerts->Invitation_Accept_Link=$this->Get_Invitation_Accept_Link($Workroom_Id,$to_author_id,$Alert_Id);
			$InvAlerts->Invitation_Sender=get_the_author_meta('display_name', $author_id);
			ob_start();
			include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-invitation-email-template.php');
			$Get_Template=ob_get_clean();
			ob_start();
			include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
			$css=ob_get_clean();
			wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
			wp_mail(get_the_author_meta('user_email',$to_author_id), $Alert_Title,$css.$Get_Template);
			//Add Alerts
			$collection=array(
				'Edd_Alert_Message'=>$Get_Template,
				'Edd_Alert_Title'=>$Alert_Title,
				'Edd_Alert_To'=>$to_author_id,
				'Edd_Alert_Id'=>$Alert_Id,
				'Edd_Alert_From'=>$author_id,
				'Edd_Alert_Message_Url'=>$this->Get_Invitation_Accept_Link($Workroom_Id,$to_author_id,$Alert_Id,false)
			);
			$this->update_collaborators_invitation($Workroom_Id,'Invited',$to_author_id,$Alert_Id);
			edd_add_alerts($collection);
			
			
			//End Alerts
			
			$status=array('Collaborator_Id'=>$Collaborator_Id);
			echo json_encode($status);
		}
	}
	function Get_Invitation_Accept_Link($Workroom_Id,$Collaborator_Id,$Alert_Id='',$Anchor=true){
		$inv_Page=get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$Workroom_Id."/?Alert_Id=".$Alert_Id;
		if($Anchor){
		$nonce = wp_create_nonce("accept_invitation_nonce");
    	$link = admin_url('admin-ajax.php?action=Accept_Invitation&Workroom_Id='.$Workroom_Id.'&Collaborator_Id='.$Collaborator_Id.'&Alert_Id='.$Alert_Id.'&nonce='.$nonce);
		return "<a href='".$link."'>Click Here to Accept Invitation</a> OR <a href='".$inv_Page."'>Click Here to got to Notification page</a>";
		}else{
			return $inv_Page;
		}
	}
	function Accept_Invitation(){
		$Workroom_Id=$_GET['Workroom_Id'];
		$Action='Accepted';
		$Collaborator_Id=$_GET['Collaborator_Id'];
		$Alert_Id=$_GET['Alert_Id'];
		update_post_meta($Alert_Id,'Is_Alert_Read','YES');
		$this->update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id);
	}
	function update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id=''){
		update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id);
	}
	function set_error($e){
		$status=array('Status'=>'error','Message'=>$e,'Color'=>'red','URL'=>'stay');
		echo json_encode($status);
		die();
	}
	function set_success($e){
		$status=array('Status'=>'success','Message'=>$e,'Color'=>'green','URL'=>'stay');
		echo json_encode($status);
		die();
	}
	function Send_Invitation_Email_Notification(){
		ob_start();
		global $wpdb;
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-invitation-email-template.php');
		$Invitation_Email_Template=ob_get_clean();
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
		$css=ob_get_clean();
		wp_mail('alexsharma68@gmail.com', 'This is test Email collaboration',$css.$Invitation_Email_Template);
	}
	/* Alerts Starts */
	function Delete_Alerts(){
		$Delets_IDs=array();
		if(is_array($this->posts[ 'Alert_Checkboxs' ])){
			foreach($this->posts[ 'Alert_Checkboxs' ] as $ID){
				wp_delete_post($ID);
				$Delets_IDs[]=$ID;
			}
		}
		echo json_encode($Delets_IDs);
	}
	function Filter_Workroom(){
		
		$arg=array(
						'author'=>get_current_user_id(),
						'collaboration_status'=>$_POST['Type']
					);
		$workrooms=get_workroom($arg);
		$workrooms_list=array();
		foreach($workrooms as $workroom){
							$params=$workroom->ID;
							$workroom_leader=get_post_field('post_author',get_main_item_id_by_workroom_id($params));
							if(in_array($params,$workrooms_list)) continue 1;
							$workrooms_list[]=$params;
							?>
						   <tr class="odd first inprogress" data-workroom-id="<?php echo $params; ?>">
									<td class="workrooominformation">
                                            <div class="workroomitemtitle">
                                                <a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/message')).$params; ?>">
            
													<?php echo get_the_title($params); ?>
                                               
                                                </a>
                                            </div>
                                            <div class="workroominfoadditional">
                                           	 <?php echo get_collaboration_status_by_main_item_id($params); ?> <?php echo get_the_date('Y-m-d', $params); ?> | Workroom Lead: 
                                            	<a href="javascript:void(0);"><?php echo ($workroom_leader==get_current_user_id())?'You':get_the_author_meta('display_name', $workroom_leader); ?></a>
                                        </div>
									</td>
									<td class="wrlistitemactions">
										<div class="wrlistitemactionsholder">
											<div class="wrlistitemactionstxt">
												Actions
											</div>
											<ul class="writemactionslist">
												<li class="first"><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/message')).$params; ?>">Messages</a></li>
												<li><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/files')).$params; ?>">Files</a></li>
												<li><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members')).$params; ?>">Team Members</a></li>
												<li><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-commission')).$params; ?>">Commission</a></li>
												<li><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$params; ?>">Alerts</a></li>
												<!--<li><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/report-problem')).$params; ?>">Report a Problem</a></li>-->
                                                 <?php
                                                if(get_post_field('post_author',$params)==get_current_user_id()){
                                                ?>
                                                <li class="last"><a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/add-workroom')).$params; ?>">Edit Workroom</a></li>
                                                <?php
												}
												if(get_post_field('post_author',$params)==get_current_user_id()){
												?>
                                                <li><a href="JavaScript:void(0)" onClick="Edd.collaboration.Workroom.Delete('<?php echo $params; ?>')">Delete Workroom</a></li>
												<?php
												}
												?>
											</ul>
										</div>
									</td>
								</tr>
                                
							<?php
						 } 
		
	}
	function Report_Problem(){
		$Name =get_the_author_meta('display_name', get_current_user_id()); // $current_user->user_firstname .' ' .$current_user->user_lastname;
		$Reason=$_POST['Reason'];
		$Title=$_POST['Title'];
		if($Title=='')$this->set_error('Please fillout Title textfield');
		if($Reason=='')$this->set_error('Please fillout reason textfield');
		$Workroom_Id=$_POST['Workroom_Id'];
		$date=date('Y-m-d H:i:s');
		$post = array(
		  'post_content'   =>$Reason,
		  'post_name'      =>$Title,
		  'post_title'     =>$Title,
		  'post_status'    =>'publish',
		  'post_type'      =>'collab-problem',
		  'post_author'    => get_current_user_id(),
		  'ping_status'    =>'close',
		  'post_parent'    => '0',
		  'menu_order'     =>'0',
		  'to_ping'        =>'',
		  'pinged'         => '',
		  'post_password'  =>'',
		  'post_date'      =>$date,
		  'comment_status' => 'closed',
		);
		$Problem_Id   = wp_insert_post( $post );
		update_post_meta($Workroom_Id, 'Collaboration_Status','in-dispute');
		update_post_meta($Problem_Id, 'Workroom_Id',$Workroom_Id);
		$son=array(
			"Status"=>'success',
			"Workroom_Id"=>$Workroom_Id,
			"Message"=>"Thanks for reporting problem. This workroom will be in Dispute - Our team will do required further action."
		);
		echo json_encode($son);
	}
	function Change_Status(){
		$Workroom_Id=$_POST['Workroom_Id'];
		$Change_To=$_POST['Change_To'];
		update_post_meta($Workroom_Id, 'Collaboration_Status',$Change_To);
	}
	function user_like(){
		$key='user_likes';
		if($_POST['type']=='music'){
			$key='user_likes_music';
		}
		//delete_post_meta($_POST['product_id'],'user_likes');
		$old=get_post_meta($_POST['product_id'],$key);
		if(!is_array($old)){
		$old=array();	
		}
		$old=$old[0];
		$old[get_current_user_id().'-'.get_the_user_ip()]=array('User_ID'=>get_current_user_id(),'IP'=>get_the_user_ip());
		update_post_meta($_POST['product_id'],$key,$old);
		$data=get_post_meta($_POST['product_id'],$key);
		echo '{"status":"Ok","total":"'.sizeof($data[0]).'"}';
	}
}

?>
