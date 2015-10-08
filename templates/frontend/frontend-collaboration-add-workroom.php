<?php
$ID=(int) get_query_var("workroom_id");
$workroom_name='';
$workroom_description='';

if($ID>0 && $ID!=''){
	$data=get_post($ID);
	$workroom_name=$data->post_title;
	$workroom_description=$data->post_content;
}
?>
<div class="wrapper_collab">
    <div class="collabadditem">
        <a href="<?php echo get_permalink(get_page_by_path('collaboration')); ?>" class="sabutton">Back to Workrooms Page</a>
		
        <div class="clear"></div>
    </div>
	
    <div class="clear"></div>
	
	<div class="wrapper_collabinside">
		<form action="" method="post" id="WORKROOM_FORM">
			<input name="ITEM_ID" type="hidden" value="<?php echo $ID; ?>" />
			<input name="USER_ID" type="hidden" value="<?php echo get_current_user_id(); ?>" />
			
			<div class="newworkroomform">
				<div class="newworkroomform_inside">
					
					<div class="newworkroommessage">
                        <div id="GLOBAL_MESSAGE_TOP"></div>
					</div>
					
					<div class="workroomformrow odd first">
						<label for="workroom_name">Workroom Name</label>
						<div class="workroomformfld">
							<input type="text" name="workroom_name" id="workroom_name" class="text" value="<?php echo $workroom_name; ?>" />
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="workroomformrow even">
						<label for="workroom_description">Workroom Description</label>
						<div class="workroomformfld">
							<textarea id="workroom_description" name="workroom_description"><?php echo $workroom_description; ?></textarea>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php
						//get_unsold_unfinished_purchase_of_users
						$unf=get_unfinished_purchase_of_users(get_current_user_id());
						//echo "<pre>";
						//print_r($unf);
						
					?>
					<div class="workroomformrow odd">
						<label for="Unfinished_Item_ID">Select an Unfinished Item for Collaboration</label>
						<div class="workroomformfld">
							<select style="max-height: 200px" 
								class="select" 
								size="4" 
								name="Unfinished_Item_ID" 
								id="Unfinished_Item_ID" 
								readonly="readonly">
								<?php
								foreach($unf as $inf){
									$existing_workroom=get_already_created_workroom(get_current_user_id(),$inf->ID);
									$disabled='';
									$selected='';
									
									if(sizeof($existing_workroom)>0){
										//EDIT | 
										if($existing_workroom[0]->ID!=$ID){
											$disabled='disabled';
										}
										if($existing_workroom[0]->ID==$ID){
											$selected='selected="selected"';
										}
									}else{
										if($ID!=''){
											$disabled='disabled';
										}
									}
									?>
									<option value="<?php echo $inf->ID; ?>" <?php echo $disabled; ?> <?php echo $selected; ?>>
										<?php echo get_the_title($inf->ID); ?>
									</option>
									<?php
								}
								?>
							</select>
							
							<div class="txtnotice">
								<span class="noticelabel">Notice:</span>
								<span class="noticetext">If you've already created a workroom with an unfinished item, it cannot be reused to make another workroom.</span>
							</div>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="workroomformrow even last">
						<div class="workroomformfld">
							<button id="ADD_WORKROOM_BUTTON" class="sabutton" name="ADD_WORKROOM_BUTTON" type="button">
								<?php echo ($ID != '') ? 'Update' : 'Add'; ?> Workroom
							</button>
						</div>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
		</form>
		
		<div class="clear"></div>
	</div>
    
    <div class="clear"></div>
</div>

<div class="clear"></div> 
