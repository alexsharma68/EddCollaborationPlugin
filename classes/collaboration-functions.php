<?php
//$pages=get_option('collab_exclude_pages');
			//print_r($pages);
add_filter('parse_query', 'collab_exclude_pages' );
function collab_exclude_pages($query) {
			global $pagenow,$post_type;
			$pages=get_option('collab_exclude_pages');
			if (is_admin() && $pagenow=='edit.php' && $post_type =='page') {
				$query->query_vars['post__not_in'] = $pages;
			}
		}
function get_the_user_ip() {
$ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
       $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}
function get_user_like($id=0,$type='product'){
	if($type=='product'){
		$data=get_post_meta($id,'user_likes');
	}else{
		$data=get_post_meta($id,'user_likes_music');
	}
	return sizeof($data[0]);
}
function get_unfinished_type_status(){
	$arrays=get_option('unfinished_item_types');
	return $arrays=explode(',',$arrays);
}
function get_categories_ids(){
	return array('67'=>'Compose','4'=>'Design','80'=>'Write');
}
function get_categories_ids_only(){
	return array('67','4','80');
}
function get_categories_structure(){
	return $data=array(
		'finished'=>array(
			'10'=>array('key'=>'audio','text'=>'I am uploading Music/Audio File.'),
			'25'=>array('key'=>'literature','text'=>'I am uploading Literature File.'),
			'76'=>array('key'=>'design','text'=>'I am uploading Design File.')
		),
		'unfinished'=>array(
			'67'=>array('key'=>'compose','text'=>'I am uploading Compose File.'),
			'4'=>array('key'=>'design','text'=>'I am uploading Design File.'),
			'80'=>array('key'=>'write','text'=>'I am uploading Write File.')
		)
	);
}
function is_finished($status){
	if(strtolower($status)=='completed' || strtolower($status)=='finished' || strtolower($status)=='finish' || strtolower($status)=='complete'){
		return true;
	}
	
	return false;
}
function show_checkboxes($Workroom_Id){
	$rows=get_teams_as_workroom($Workroom_Id);
	$show=true;
	$total_amounts_entered=0;
	foreach($rows as $records){
		$author_id = $records->Collaborator_Id;
		$status=$records->Invitation_Status;
		
		if($status!='Accepted') continue 1;
		$total_amounts_entered=$total_amounts_entered+get_user_meta($author_id,'download-'.$Workroom_Id,true);
		if(get_user_meta($author_id,'download-'.$Workroom_Id,true)==''){
			$show=false;
		}	
	}
	return ($total_amounts_entered=='100' && $show)?true:false;

}
function status_collections(){
	return array('in-progress'=>'In Progress - Started','in-dispute'=>'In Dispute','complete'=>'Complete');
}

function collaboration_status_test($e){
	$all_status=status_collections();
	
	if(isset($all_status[$e])){
		return $all_status[$e];
	}
}

function admin_filter_type($e){
	$all_status=array('in_progress'=>'in-progress','in_dispute'=>'in-dispute','complete'=>'complete');
	if(isset($all_status[$e])){
		return $all_status[$e];
	}
}

function get_files($workroom_id='',$Alert_Id=''){
	$args = array(
		'post_type' 	=> 'collab-files',
		'orderby'		=> 'ID',
		'order' 		=> 'DESC',
		'posts_per_page'   =>-1,
	);
	
	if($workroom_id!=''){
		$args['meta_query']=array(
			'relation' 	=> 'AND',
			array(
				'key' 	=> 'Workroom_Id',
				'value' => array($workroom_id),
			),
		);
	}
	
	if($workroom_id!='' && $Alert_Id!=''){
		$args['meta_query'][]=array(
			'key' 		=> 'Edd_Alert_Id',
			'value' 	=> $Alert_Id,
			'compare' 	=> '='
		);
	}
	
	$message = new WP_Query( $args );
	
	return $posts = $message->get_posts();
}

function get_messages($Workroom_Id,$Alert_Id=''){
	$args = array(
		'meta_query' 	=> array(
			array(
				'key' 	=> 'Workroom_Id',
				'value' => array($Workroom_Id),
			),
		),
		'post_type' 	=> 'collab-msg',
	);
	
	if($Alert_Id!=''){
		$args['meta_query'][]=array(
			'key' 		=> 'Edd_Alert_Id',
			'value' 	=> $Alert_Id,
			'compare' 	=> '='
		);
	}
	
	$message = new WP_Query( $args );
	
	return $message->get_posts();
}

function get_recent_user_files($workroom_id){
	$args = array(
		'meta_query' 	=> array(
			array(
				'key' 	=> 'Workroom_Id',
				'value' => array($workroom_id),
			),
		),
		'post_type' 	=> 'collab-files',
		//'post_author' => $e,
		'orderby'		=> 'ID',
		'order' 		=> 'desc',
	);
	
	$message = new WP_Query( $args );
	
	return $posts = $message->get_posts();
}

function formatSizeUnits($bytes) {
	$types = array( 'Byte', 'KB', 'MB', 'GB', 'TB' );
       // for( $i = 0; $bytes >= 1024 && $i < ( count( $types ) -1 ); $bytes /= 1024, $i++ );
               // return( round( $bytes, 2 ) . " " . $types[$i] );
				
				
				
	if ($bytes >= 1000000000) {
		$bytes = number_format($bytes / 1000000000, 2) . ' GB';
	} elseif ($bytes >= 1000000) {
		$bytes = number_format($bytes / 1000000, 2) . ' MB';
	} elseif ($bytes >= 1000) {
		$bytes = number_format($bytes / 1000, 2) . ' KB';
	} elseif ($bytes > 1) {
		$bytes = $bytes . ' Bytes';
	} elseif ($bytes == 1) {
		$bytes = $bytes . ' Byte';
	} else {
		$bytes = '0 bytes';
	}

	return $bytes;
}

function UriSegment($e=0){
	$urlArray = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$segments =array_filter(explode('/', $urlArray));
	$numSegments = count($segments);
	
	return $currentSegment = $segments[$numSegments - $e];
}

function is_belong_to_you($post_id){
	if(get_post_field('post_author',$post_id)==get_current_user_id())return true;
		$child_id=get_post_meta($post_id,'Edd_Child',true);
	if($child_id=='')return false;
		return is_belong_to_you($child_id);
}

function get_list_of_parent_items($post_id,$list){
	$child_id=get_post_meta($post_id,'Edd_Parent',true);
	
	if($child_id==''){
		return $list;
	}
	
	array_push($list,$child_id);
	
	return get_list_of_parent_items($child_id,$list);
}

function get_parent_item($post_id){
	$child_id=get_post_meta($post_id,'Edd_Parent',true);
	if($child_id=='')return $post_id;
	return get_parent_item($child_id);
}

function get_product_status($e){
	$get_status=get_post_meta(get_last_product_this_item($e),'_edd_product_status',true);
	if(in_array($get_status,get_unfinished_type_status())){
		return 'In Progress';
	}elseif($get_status=='complete'){
		return "Complete";
	}elseif($get_status=='in-dispute'){
		return "In Dispute";
	}	
}

function get_last_product_this_item($post_id){
	$child_id=get_post_meta($post_id,'Edd_Child',true);
	if($child_id=='')return $post_id;
	
	return get_last_product_this_item($child_id);
}

function get_lastest_status($download_id){
	$Invitation_Status=get_post_meta($download_id,'Invitation_Status',true);
	
	if(is_array($Invitation_Status) && sizeof($Invitation_Status)>0){
		$Invitation_Status=array_reverse($Invitation_Status);
		return isset($Invitation_Status[0]['Status'])?$Invitation_Status[0]['Status']:'Processing';
	}
	
	return 'Processing';
}

function get_already_created_workroom($id=0,$item_id){
	global $wpdb;
	$args = array(
		'post_type' 		=> 'edd_workroom',
		'meta_query' 		=> array(
			'relation' 		=> 'AND',
			array(
				'key' 		=> 'Unfinished_Item_ID',
				'compare' 	=> '=',
				'value' 	=> $item_id
			)
		),
		'post_author'		=> $id,
		'orderby'			=> 'ID',
		'order' 			=> 'desc',
	);
	$message = new WP_Query( $args );
	
	return $posts = $message->get_posts();
}

function edd_add_alerts($e){
	/*
	$collection=array(
		'Edd_Alert_Message'=>'This is test',
		'Edd_Alert_To'=>'4',
		'Edd_Alert_From'=>'5',
		'Edd_Alert_Message_Url'=>'Edd_Alert_Message_Url'
	);
	edd_add_alerts($collection);
	*/
	$Edd_Alert_Message=isset($e['Edd_Alert_Message'])?$e['Edd_Alert_Message']:'';
	$Edd_Alert_To=isset($e['Edd_Alert_To'])?$e['Edd_Alert_To']:'';
	$Edd_Alert_From=isset($e['Edd_Alert_From'])?$e['Edd_Alert_From']:'';
	$Edd_Alert_Message_Url=isset($e['Edd_Alert_Message_Url'])?$e['Edd_Alert_Message_Url']:'';
	$Edd_Alert_Id=isset($e['Edd_Alert_Id'])?$e['Edd_Alert_Id']:'';
	$Edd_Alert_Title=isset($e['Edd_Alert_Title'])?$e['Edd_Alert_Title']:'';
	
	$post = array(
		'post_content'   => $Edd_Alert_Message,
		'post_name'      => 'Edd_Alerts',
		'post_title'     => $Edd_Alert_Title,
		'post_status'    => 'publish',
		'post_type'      => 'Edd_Alerts',
		'post_author'    => $Edd_Alert_To,
		'ping_status'    => 'close',
		'post_parent'    => '0',
		'menu_order'     => '0',
		'to_ping'        => '',
		'pinged'         => '',
		'post_password'  => '',
		'comment_status' => 'closed',
		'post_category'  => '',
	);
	
	$page_id = wp_insert_post( $post );
	update_post_meta($page_id, 'Edd_Alert_From',$Edd_Alert_From);
	update_post_meta($page_id, 'Edd_Alert_Message_Url',$Edd_Alert_Message_Url);
	update_post_meta($page_id, 'Edd_Alert_Id',$Edd_Alert_Id);

	return $page_id;
}

function is_post_exists($id) {
	global $wpdb;
	
	return $wpdb->get_row("SELECT * FROM $wpdb->posts WHERE ID = '" . $id . "'", 'ARRAY_A');
}

function get_alert_id_by_meta_value($value){
	global $wpdb;
	$results = $wpdb->get_row( "select m.post_id, m.meta_key from $wpdb->postmeta m,$wpdb->posts p where m.post_id=p.ID AND m.meta_value = '".$value."' and p.post_type='edd_alerts'");
	
	return $results->post_id;
}

function get_post_id_by_meta_value($value){
	global $wpdb;
	$results = $wpdb->get_row( "select m.post_id, m.meta_key from $wpdb->postmeta m,$wpdb->posts p where m.post_id=p.ID AND m.meta_value = '".$value."'");
	
	return $results->post_id;
}

//===================
function get_item_id_by_workroom_id($e){
	$unfinished=get_post_meta($e,'Unfinished_Item_ID',true);
	if(get_post_meta($unfinished,'Edd_Duplicated_Item',true)!=''){
		return get_post_meta($unfinished,'Edd_Duplicated_Item',true);
	}else{
		return $unfinished;
	}
	
}

function get_main_item_id_by_workroom_id($e){
	$item_id=get_item_id_by_workroom_id($e);
	return get_parent_item($item_id);
}

function get_workroom($e){
	$arg=array(
	  'post_status'    =>'publish',
	  'post_type'      =>'edd_workroom',
	);
	
	if(isset($e['collaboration_status']) && (strtolower($e['collaboration_status'])!='all' && $e['collaboration_status']!='')){
		$arg['meta_query']=array(
			'relation' => 'AND',
			array(
				'key' 		=> 'Collaboration_Status',
				'value' 	=> $e['collaboration_status'],
				'compare' 	=> '='
			),
		);
	}
	
	if(isset($e['author']) && $e['author']!=''){
		$arg['author__in']=array($e['author']);
	}
	
	if(isset($_POST['s']) && $_POST['s']!=''){
		$arg['s']=$_POST['s'];
	}
	
	$post = new WP_Query( $arg );
	$post=$post->get_posts();
	
	if(isset($e['author']) && $e['author']!=''){
		$arg=array(
		  'post_status'    	=> 'publish',
		  'post_type'      	=> 'edd_workroom',
		);
		
		$arg['meta_query']=array(
			'relation' 		=> 'AND',
			array(
				'key' 		=> 'Invitation_Status_'.$e['author'],
				'value' 	=> 'Accepted',
				'compare' 	=> '='
			),
		);
		
		if(isset($e['collaboration_status']) && (strtolower($e['collaboration_status'])!='all' && $e['collaboration_status']!='')){
			$arg['meta_query'][]=array(
				'key' 		=> 'Collaboration_Status',
				'value' 	=> $e['collaboration_status'],
				'compare' 	=> '='
			);
		}
		
		if(isset($_POST['s']) && $_POST['s']!=''){
			$arg['s']=$_POST['s'];
		}
		
		$other = new WP_Query( $arg );
		$other=$other->get_posts();
		$post=array_merge($post,$other);
	}
	return $post;
}

function get_collaboration_status_by_main_item_id($e){
	$status=get_post_meta($e,'Collaboration_Status',true);
	
	if($status==''){
		$status=get_post_meta($e,'Collaboration_Status',true);
	}
	
	return collaboration_status_test($status);
}

function get_only_unfinished_item_id_by_user_created_workroom($e){
	$arg=array(
		'post_status'   => 'publish',
		'post_type'     => 'edd_workroom',
		'author__in' 	=> array($e)
	);
	
	$post = new WP_Query( $arg );
	$post=$post->get_posts();
	$IDs=array();
	
	foreach($post as $item){
		$IDs[]=(int) get_post_meta($item->ID,'Unfinished_Item_ID',true);
	}
	
	return $IDs;
}

function get_unsold_unfinished_purchase_of_users($user_id){
	$total_unfinished_purchased=array();
	
	$args = array(
		'post_type'     	=> 'edd_unfinished_sold',
		'post_status'   	=> 'publish',
		'post__not_in' 		=> get_only_unfinished_item_id_by_user_created_workroom(get_current_user_id()),
		'meta_query' 		=> array(
			'relation' 		=> 'AND',
			array(
				'key' 		=> 'is_posted',
				'value' 	=> '1',
				'compare' 	=> 'NOT EXISTS'
			)
		),
	);
	
	if($user_id!=''){
		$args['author__in']=array($user_id);
	}
	
	//$post = new WP_Query($args);
	
	return get_posts($args);
}

function get_unfinished_type(){
	return get_completed_status();
}
function get_completed_status(){
	return array('complete','Complete','finish','Finished','Finish');
}

function get_unfinished_purchase_of_users($user_id=''){
	$total_unfinished_purchased=array();
	$args=array(
		'post_type'     	=> 'edd_unfinished_sold',
		'post_status'   	=> 'publish',
	);
	if($user_id!=''){
		$args['author__in']=array($user_id);
	}
	$post = new WP_Query($args);
	return $post->get_posts();
}

function get_list_of_childs($post_id,$arr=array()){
	$meta=get_post_meta($post_id,'Edd_Child');
	if(is_array($meta) && sizeof($meta)>0){
		foreach($meta as $vels){
			array_push($arr,$vels);
			return get_list_of_childs($vels,$arr);
		}
	}else{
		return $arr;
	}
	/*
	if($meta!=''){
		$arr[]=$meta;
		return get_list_of_childs($meta,$arr);
	}else{
		return $arr;
	}
	*/
}

function has_child($post_id){
	$meta=get_post_meta($post_id,'Edd_Child',true);
	if($meta!='')return true;
	
	return false;
}

function has_parent($post_id){
	$meta=get_post_meta($post_id,'Edd_Parent',true);
	if($meta!='')return true;
	
	return false;
}

function get_teams_as_workroom($Workroom_Id){
	$item_id=get_item_id_by_workroom_id($Workroom_Id);
	$main=get_main_item_id_by_workroom_id($Workroom_Id);
	
	$all_parents=get_list_of_parent_items($item_id,array($item_id));
	$all_childs=get_list_of_childs($item_id,$all_parents);
	foreach($all_childs as $records){
		$author_id = get_post_field( 'post_author', $records );
		$status=get_post_meta($Workroom_Id,'Invitation_Status_'.$author_id,true);
		$collects_items[$author_id]=(object) array(
			'Collaborator_Id'	=> $author_id,
			'Invitation_Status'	=> $status,
			'Item_Id'			=> $records,
			'Main_Item_Id'		=> $main
		);
	}
	
	return $collects_items;
}


function get_users_collab_workroom(){
	if ( is_user_logged_in() && rcp_get_subscription_id($current_user->ID)==1) {
	return 	' 	<li>
					<a href="'.get_permalink(get_page_by_path('collaboration')).'">
						<i class="fa fa-comments"></i>
						<span>Workrooms</span>
					</a>';
	}
}


function get_users_collab_alerts(){
	if ( is_user_logged_in() && rcp_get_subscription_id(get_current_user_id())==1) {
	$alerts=edd_get_unread_alerts(get_current_user_id());
	$styles='';
	//if(sizeof($alerts)>0){
		$styles='<span id="Alert_Count" class="nav_notice_counter">'.sizeof($alerts).'</span>';
	//}
	$str.=' <li class="collabnoticeparent">
				<a href="JavaScript:void(0)">
					<i class="fa fa-envelope"></i>
					<span>Notifications '.$styles.'</span>
				</a>';
	
	if(sizeof($alerts)>0){
		$str.='<ul class="collabnotices" id="alert_ul">';
		foreach($alerts as $val){
			$url=get_post_meta($val->ID,'Edd_Alert_Message_Url',true);
			$str.=' <li data-alert-id="'.$val->ID.'">
						<a href="'.$url.'">
							<span>'.$val->post_title.'</span>
						</a>
					</li>';
		}
		$str.='</ul>';
	}
	$str.='</li>';
	
	return $str;
	}
}

function edd_get_unread_alerts($e){
	$post=array(
		'post_status'		=> 'publish',
		'post_type'      	=> 'Edd_Alerts',
		'orderby'         	=> 'post_date',
		'order'            	=> 'DESC',
		'posts_per_page'   	=> 5,
		'author__in'		=> array($e),
		'meta_query' 		=> array(
			'relation' => 'AND',
				array(
					'key' 		=> 'Is_Alert_Read',
					'value' 	=> '1',
					'compare' 	=> 'NOT EXISTS'
				)
		),
	);
	
	$post = new WP_Query( $post );
	
	return $post->get_posts();
}

function edd_get_alerts($e,$Alert_Id=''){
	$post=array(
		'post_status'			=> 'publish',
		'post_type'     		=> 'Edd_Alerts',
		'author__in'			=> array($e)
	);
	
	if($Alert_Id!=''){
		$post['meta_query'] = array(
			'relation' 			=> 'AND',
				array(
					'key' 		=> 'Edd_Alert_Id',
					'value' 	=> $Alert_Id,
					'compare' 	=> '='
				),
			);
	}
	$post = new WP_Query( $post );
	return $post->get_posts();
}

function delete_post_meta_by_post_id($e){
	global $wpdb;
	$wpdb->query($wpdb->prepare("DELETE FROM $wpdb->postmeta WHERE post_id = %d",$e));
}

function get_email_domain(){
	return '';
}

function create_workroom_email_account($e){
	// APi Created by Durga.
	/*
	$cpuser = '';
	$cppass = '';
	$cpdomain =get_email_domain();
	$cpskin = 'x3';
	$euser = $e;
	$epass = 'sharearts@123';
	$edomain =get_email_domain();
	$equota = (int) get_option('max_quota');
	$f = fopen (get_option('email_server')."?email=$euser&domain=$edomain&password=$epass&quota=$equota", "r");
	if (!$f) {
		return false;
	}
	$what=false;
	while (!feof ($f)) {
		$line = fgets ($f, 1024);
		if (preg_match( "/already exists/", $line )) {
			$what=false;
			break;
		}else if(preg_match( "/“created”/", $line )){
			$what=true;
			break;
		}
	}
	@fclose($f);
	return $what;
	*/
	return true;
}

function get_list_of_collaborators($Args){
	$Workroom_Id=get_post_meta($Args->ID,'Workroom_Id',true);
	if($Workroom_Id!=''){
		$Workroom_Teams=get_teams_as_workroom($Workroom_Id);
		foreach($Workroom_Teams as $vals){
			if($vals->Collaborator_Id=='')continue 1;
			if($vals->Collaborator_Id==get_the_author_meta('ID'))continue 1;
			?>
			<div class="author Thumb">
				<?php do_action('sharearts_download_author_before'); ?>
				<?php printf('<a class="author-link" href="%s" rel="author">%s</a>', sharearts_edd_fes_author_url($vals->Collaborator_Id), get_avatar($vals->Collaborator_Id, 130)); ?>
				<?php printf('<a class="author-link" href="%s" rel="author">%s</a>', sharearts_edd_fes_author_url($vals->Collaborator_Id), get_the_author_meta('display_name',$vals->Collaborator_Id)); ?>
				<span class="author-joined"><?php printf(__('Author since: %s', 'sharearts'), date_i18n(get_option('date_format'), strtotime(get_the_author_meta('user_registered',$vals->Collaborator_Id)))); ?></span>
				<?php do_action('sharearts_download_author_after'); ?>
			</div>
			<?php
		}
	}
}

function update_collaborators_invitation($Workroom_Id,$Action,$Collaborator_Id,$Alert_Id=''){
	update_post_meta($Workroom_Id,'Invitation_Status_'.$Collaborator_Id,$Action);
	$Create_Invitation_Status=get_post_meta($Workroom_Id,'Invitation_Status',true);
	
	if(!is_array($Create_Invitation_Status)){
		$Create_Invitation_Status=array();
	}
	
	$Create_Invitation_Status[]=array('Collaborator_Id'=>$Collaborator_Id,'Status'=>$Action,'Action'=>'---','Invited_Data'=>date("Y-m-d H:i:s"),'Alert_Id'=>$Alert_Id);
	update_post_meta($Workroom_Id,'Invitation_Status',$Create_Invitation_Status);
}

function get_collaboration_setup_commission($Workroom_Id){
	$Commission_Amounts=array();
	$Commission_Collaborators=array();
	$Collaborators=get_teams_as_workroom($Workroom_Id);
	
	foreach($Collaborators as $Item){
		$Collaborator_Id=$Item->Collaborator_Id;
		$Invitation_Status=$Item->Invitation_Status;
		$Main_Item_Id=$Item->Main_Item_Id;
		
		if($Invitation_Status!='Accepted') continue 1;
		$Get_Commission_Amount=0;
		$Get_Commission_Rate=(int)eddc_get_recipient_rate(0,get_post_field('post_author',$Main_Item_Id));
		$Get_Collaboration_Commission=(int)get_user_meta($Collaborator_Id,'download-'.$Workroom_Id,true);
		
		if($Get_Commission_Rate>0 && $Get_Collaboration_Commission>0){
			$Get_Commission_Amount=($Get_Commission_Rate/100)*$Get_Collaboration_Commission;
		}
		
		$Commission_Amounts[]=(int)$Get_Commission_Amount;
		$Commission_Collaborators[]=(int)$Collaborator_Id;
	}
	$commission = array(
		'amount' 	=> implode(',',$Commission_Amounts),
		'user_id' 	=> implode(',',$Commission_Collaborators),
		'type' 		=> 'percentage',
		'from' 		=> 'unfinished',
	);
	return $commission;
}

function set_collaborators_regular_unfinished_commission($args){
	$post_id=(int)$args['post_id'];
	$new_post_id=(int)$args['new_post_id'];
	$commission=array();
	$old_commission=get_post_meta($post_id,'_edd_commission_settings',true);
	
	if(isset($old_commission['user_id'])){
		$Main_Item_Id=get_parent_item($new_post_id);
		$users=explode(',',$old_commission['user_id']);
		$users[]=get_current_user_id();
		$Get_Commission_Rate=(int)eddc_get_recipient_rate(0,get_post_field('post_author',$Main_Item_Id));
		$Commission_Amounts=array();
		
		foreach($users as $Item){
			$Get_Commission_Amount=$Get_Commission_Rate/sizeof($users);
			$Commission_Amounts[]=(int) $Get_Commission_Amount;
			$Commission_Collaborators[]=$Item;
		}
		
		$commission = array(
			'amount' 	=> implode(',',$Commission_Amounts),
			'user_id' 	=> implode(',',$Commission_Collaborators),
			'type' 		=> 'percentage',
			'from' 		=> 'unfinished',
		);
	}
	
	update_post_meta($new_post_id,'_edd_product_status',$args['_edd_product_status']);
	update_post_meta($new_post_id,'_edd_commission_settings',$commission);
}

function check_product_status_before_save($post_id){
	/*
	if(isset($_REQUEST['_edd_product_status']) && isset($_REQUEST['_edd_product_status'][0])){
		$status=$_REQUEST['_edd_product_status'][0];
		if(!is_finished($status)){
			echo " - die - ";
			$response    = array(
				'success' => true,
				'message' => __('Cound not save, Same Status already posted by another user', 'edd_fes' ),
				'is_post' => false
			);
			echo json_encode( $response );
			
		}
	}
	die();
	*/
}

function get_previous_all_status($e,$options=array(),$Add_Current=true){
	$current_status=get_post_meta($e,'_edd_product_status',true);
	$list=array();
	$add=false;
	
	foreach($options as $values){
		if($values==$current_status && $add==false)$add=true;
		if($values==$current_status && $Add_Current==false) continue 1;
		
		if($add){
			$list[]=$values;
		}
	}
	if($e==0 || $e=''){
		$list=$options;
	}
	
	return json_encode($list);
}

function get_unfinished_options(){
	$form_id = EDD_FES()->helper->get_option( 'fes-submission-form', false );
	$form_vars = get_post_meta( $form_id, 'fes-form', true );
	$options=array();
	
	foreach($form_vars as $vals){ 
		if($vals['name']=='_edd_product_status'){
			$options=$vals['options'];
		}
	}	
	
	return $options;
}

function eddc_get_from( $download_id=0) {
	if(!empty( $download_id ) ) {
		$settings = get_post_meta( $download_id, '_edd_commission_settings', true );
		return $settings['amount'];
	}
}

function was_unfinished($id){
	$Workroom=get_post_id_by_meta_value($id);
	$Parent=has_parent($id);
	return ($Parent || $Workroom)?true:false;
}

function get_reported_problems(){
	$post=array(
		'post_status'    	=> 'publish',
		'post_type'      	=> 'collab-problem',
		'orderby'          	=> 'post_date',
		'order'            	=> 'DESC',
		'posts_per_page'   	=> -1,
	);
	
	if(isset($_POST['s']) && $_POST['s']!=''){
		$post['s']=$_POST['s'];
	}
	
	$post = new WP_Query( $post );
	return $post->get_posts();
}

function get_reported_problems_messages($e){
	$post=array(
		'post_status'    	=> 'publish',
		'post_type'      	=> 'collab-msg-rep',
		'orderby'          	=> 'post_date',
		'order'            	=> 'DESC',
		'posts_per_page'   	=> -1,
	);
	
	if(isset($_POST['s']) && $_POST['s']!=''){
		$post['s']=$_POST['s'];
	}
	
	$post['meta_query'] = array(
		'relation' => 'AND',
		array(
			'key' 		=> 'Report_Id',
			'value' 	=> $e,
			'compare' 	=> '='
		),
	);
	
	$post = new WP_Query( $post );
	
	return $post->get_posts();	
}
function add_unfinished_record($download){
		$download_id    		= absint( $download['id'] );

		$commissions_enabled  	= get_post_meta($download_id, '_edd_commisions_enabled', true );

		if ( 'subtotal' == edd_get_option( 'edd_commissions_calc_base', 'subtotal' ) ) {

			$price = $download['subtotal'];

		} else {

			$price = $download['price'];

		}
		/* By Durga */
		//Check if product is sold as unfinished;
		
		$Collaboration_Product_Status=get_post_meta($download_id,'_edd_product_status');
		if(is_array($Collaboration_Product_Status) && !is_finished($Collaboration_Product_Status[0])){
			$download_id=commission_keep_records($download_id);
			
		}

}
function commission_keep_records($post_id){
		global $wpdb;
		
		$PreviousPost = get_post($post_id);
		$New_Post=array(
							'post_type'  	=> 'edd_unfinished_sold',
							'post_title'  	=> $PreviousPost->post_title,
							'post_status'  	=> 'publish',
							'post_content'  => $PreviousPost->post_content,
							'comment_status'=> $PreviousPost->comment_status,
							'ping_status'  	=> $PreviousPost->ping_status,
							'post_name'  	=> $PreviousPost->post_name,
							'post_parent'  	=> $PreviousPost->post_parent,
							'post_author'  	=> get_current_user_id(),
					);
		$new_post_id = wp_insert_post($New_Post);
		$taxonomies = get_object_taxonomies('download');
		foreach ($taxonomies as $taxonomy) {
			$post_terms = wp_get_object_terms($post_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($new_post_id, $post_terms, $taxonomy, false);
		}
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$post_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			$array=array(
			'_edd_download_sales',
			'_edd_payment_meta',
			'_edd_payment_user_id',
			'_edd_payment_customer_id',
			'_edd_payment_user_email',
			'_edd_payment_user_ip',
			'_edd_payment_purchase_key',
			'_edd_payment_total',
			'_edd_payment_mode',
			'_edd_payment_gateway',
			'_edd_has_commission',
			'_download_product_unfinished',
			'_download_product_unfinished_status',
			//'_edd_commission_info',
			//'_commission_status',
			'_download_id',
			//'_user_id',
			//'_edd_previous_download_ids',
			//'Edd_Child',
			//'Edd_Parent',
			'_edd_commission_payment_id',
			'_edd_completed_date',
			'is_posted',
		);
		update_post_meta($post_id,'Edd_Child',$new_post_id);
		update_post_meta($new_post_id,'Edd_Parent',$post_id);
			foreach ($post_meta_infos as $meta_info) {
				if(!in_array($meta_info->meta_key,$array)){
					$meta_key = $meta_info->meta_key;
					$meta_value = addslashes($meta_info->meta_value);
					$sql_query_sel[]= "SELECT $new_post_id, '$meta_key', '$meta_value'";
				}
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}
		
		return $post_id;
			
}
function update_meta_kay_value($key,$value,$nv){
	global $wpdb;
	$wpdb->query("update $wpdb->postmeta set meta_value='".$nv."' where meta_key='".$key."' AND meta_value='".$value."'");
}
function delete_post_meta_post_id_and_post_key($id,$key){
	global $wpdb;
	$wpdb->query("delete from $wpdb->postmeta where meta_key='".$key."' AND post_id='".$id."'");
}
function get_workroom_space_details($Workroom_Id){
	$total_files=get_files($Workroom_Id);
	$size=0;
	foreach($total_files as $file){
		$size=$size+get_post_meta($file->ID,'_wp_collaboration_file_size',true); 
	}
	$available_size=(int) get_option('allowed_space_in_gb'); 
	$available_size_in_kb=$available_size*1000000000;
	
	$free_space=$available_size_in_kb-($available_size_in_kb-$size);
	$dp = sprintf('%.2f',($free_space / $available_size_in_kb) * 100);
	
	return array(
	'UsedSpace'=>$size,
	'UsedSpaceWithUnit'=>formatSizeUnits($size),
	'UsedSpaceWithPercentage'=>$dp,
	'TotalSpace'=>formatSizeUnits($available_size_in_kb)
	);

	
}
function read_emails(){
	$inbox_emails=array();
	set_time_limit(4000);
 
// Connect to gmail
$imapPath ='{imap.gmail.com:993/imap/ssl/novalidate-cert}INBOX';// get_option('workroom_email_imap_server');
$username = get_option('workroom_email_account'); //'your_email_id@gmail.com';
$password = get_option('workroom_email_password');
 
// try to connect
$inbox = imap_open($imapPath,$username,$password) or die(imap_last_error());
$emails = imap_search($inbox,'ALL');//UNSEEN
//print_r($emails); die('xxxx');
if(is_array($emails)){
	foreach($emails as $mail) {

			$output = 'pppppppppppppppppppppp';
			$headerInfo = imap_headerinfo($inbox,$mail);
			$emailStructure = imap_fetchstructure($inbox,$mail);



 $overview = imap_fetch_overview($inbox,$mail,0);
        $structure= imap_fetchstructure($inbox,$mail);

        if(isset($structure->parts) && is_array($structure->parts) && isset($structure->parts[1])) {
            $part = $structure->parts[1];
            $output = imap_fetchbody($inbox,$mail,1);

            if($part->encoding == 3) {
                $output = imap_base64($output);
            } else if($part->encoding == 1) {
                $output = imap_8bit($output);
            } else {
                $output = imap_qprint($output);
            }
        }









			//$output = imap_fetchbody($inbox,$mail,1);
$output=strip_tags($output,'<a>,<b>,<ul>,<li>,<p>');
			$ar=explode("\n", $output);
			$new_array=array();
			foreach($ar as $line){
			if (strpos($line,'=20') !== false) continue 1;
			if($line=='=20')continue 1;
    				$result = substr($line, 0, 5);
				if((strpos($line,'From:')!== false) || (strpos($line,'Sent:')!== false)){
					break;
				}
$line= trim($line);
if($line=='From:' || $line=='Sent:'){
					break;
				}





//$line= preg_replace('/\s+/', '', $line);
				if($line=='') continue 1;

				$new_array[]=$line."<br>";
			}
//$new_array=array_filter($new_array);
//print_r($new_array); die();
			$mess=implode('',$new_array);
			$inbox_emails[]=(object)array(
				'Email_Subject'=>$headerInfo->subject,
				'Email_To_Address'=>$headerInfo->toaddress,
				'Email_Date'=>$headerInfo->date,
				'Email_From_Address'=>$headerInfo->fromaddress,
				'Email_Reply_To_Address'=>$headerInfo->reply_toaddress,

				'Email_Body'=>$mess
			);
			imap_delete($inbox,$mail);
	}
}else{
$inbox_emails=array();	
}
// colse the connection
imap_expunge($inbox);
imap_close($inbox);
return $inbox_emails;
}
function check_emails(){
		$emails=read_emails();
		foreach($emails as $email){
			$startsAt = strpos($emails[0]->Email_Subject, "[") + strlen("[");
			$endsAt = strpos($emails[0]->Email_Subject, "]", $startsAt);
			$result = substr($emails[0]->Email_Subject, $startsAt, $endsAt - $startsAt);
			if($result!=''){
				$check_to_insert=explode(':',$result);
				if(isset($check_to_insert[0]) && isset($check_to_insert[1]) && $check_to_insert[0]!='' && $check_to_insert[1]!=''){
					$Email_Type=$check_to_insert[0];
					$Workroom_ID=$check_to_insert[1];
					$Author_ID=$check_to_insert[2];
					if(strtolower($check_to_insert[0])=='message'){
						$post = array(
						  'post_content'   => nl2br($email->Email_Body),
						  'post_name'      => $email->Email_Subject,
						  'post_title'     => $email->Email_Subject,
						  'post_status'    =>'publish',
						  'post_type'      =>'collab-msg',
						  'post_author'    =>$Author_ID,
						  'ping_status'    =>'close',
						  'post_parent'    => '0',
						  'menu_order'     =>'0',
						  'to_ping'        =>'',
						  'pinged'         => '',
						  'post_password'  =>'',
						  'comment_status' => 'closed',
						);
						$page_id   = wp_insert_post( $post );
						//if(isset($this->posts['attachments']) && is_array($this->posts['attachments'])){
							//update_post_meta($page_id, '_wp_collaboration_attachments',$this->posts['attachments']);
						//}
						update_post_meta($page_id, 'Workroom_Id',$Workroom_ID);
						
						$Alert_Id=uniqid();
						$author_id=$Author_ID;
						$Workroom_Id=$Workroom_ID;
						
							
							$Teams=get_teams_as_workroom($Workroom_Id);
							foreach($Teams as $team){
								$Alert_Title=''.get_the_author_meta('display_name', $author_id).' posted a message in '.get_the_title($Workroom_Id).identity(array('Workroom_Id'=>$Workroom_Id,'Type'=>'Message','Collaborator_Id'=>$team->Collaborator_Id));
								if($team->Collaborator_Id==$author_id)continue 1;
								$MessageAlert=new stdClass();
								$MessageAlert->Collaborator_Name=get_the_author_meta('display_name', $team->Collaborator_Id);
								$MessageAlert->Workroom_Name=get_the_title($Workroom_Id);
								$MessageAlert->Workroom_Message=nl2br($email->Email_Body);
								$MessageAlert->Notification_Link=Get_Message_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id);
								$MessageAlert->Notification_From=get_the_author_meta('display_name', $author_id);
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
									'Edd_Alert_Message_Url'=>Get_Message_Notification_Link($Workroom_Id,$team->Collaborator_Id,$Alert_Id,false)
								);
								edd_add_alerts($collection);
								//End Add Alerts
								wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
								wp_mail(get_the_author_meta('user_email',$team->Collaborator_Id), $Alert_Title,$css.$Get_Template);
								
								
							}
						
						
					}else if(strtolower($check_to_insert[0])=='report'){
						$Alert_Title=''.get_the_author_meta('display_name',$Author_ID).' sent you a message'.identity(array('Workroom_Id'=>$Workroom_ID,'Type'=>'Report','Collaborator_Id'=>$author_id));
						save_reported(nl2br($email->Email_Body),$Alert_Title,$Workroom_ID,$Author_ID);
								
							
					}
					//print_r($email);
				}
			}
		}
		die();
	}
	function Get_Message_Notification_Link($Workroom_Id,$Collaborator_Id,$Alert_Id,$Anchor=true){
		if($Anchor){
			return "<a href='".get_permalink(get_page_by_path('collaboration/workroom/message')).$Workroom_Id."/?Alert_Id=".$Alert_Id."'>Click Here</a>";
		}else{
			return get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$Workroom_Id."/?Alert_Id=".$Alert_Id;
		}
	}
	function identity($args){
		return ' ['.$args['Type'].':'.$args['Workroom_Id'].":".$args['Collaborator_Id'].']';
	}
	function save_reported($message='',$subject='',$report_id='',$collaborator_id=0){
		
		$date=date('Y-m-d H:i:s');
		$post = array(
		  'post_content'   => $message,
		  'post_name'      => $subject,
		  'post_title'     => $subject,
		  'post_status'    =>'publish',
		  'post_type'      =>'collab-msg-rep',
		  'post_author'    =>get_post_field('post_author',$report_id),
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
		//if(isset($this->posts['attachments']) && is_array($this->posts['attachments'])){
			//update_post_meta($page_id, '_wp_collaboration_attachments',$this->posts['attachments']);
		//}
		update_post_meta($page_id, 'Report_Id',$report_id);
		/* send Emails */
		
		$Alert_Title=get_the_author_meta('display_name',$collaborator_id).' sent  you a message ';
		/*$ReportAlert=new stdClass();
		$ReportAlert->Collaborator_Name=get_the_author_meta('display_name',get_option('admin_email'));
		$ReportAlert->Notification_From=get_the_author_meta('display_name',$collaborator_id);
		$ReportAlert->Message=nl2br($message);
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-report-a-problem-template.php');
		$Get_Template=ob_get_clean();
		*/
		ob_start();
		include(collaboration_plugin_dir.'templates/frontend/emails/collaboration-email-css.php');
		$css=ob_get_clean();
	
		//Add Alert
	$Get_Template=get_the_author_meta('display_name',$collaborator_id).' sent  you a message. Please login and to  see message detail and reply.';
		//End Add Alerts
		wp_mail('alexsharma68@gmail.com', $Alert_Title,$css.$Get_Template);
		wp_mail(get_the_author_meta('user_email',$report_id), $Alert_Title,$css.$Get_Template);	
	}
	
?>