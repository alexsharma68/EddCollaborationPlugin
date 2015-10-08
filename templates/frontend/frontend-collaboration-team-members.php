<?php
$params=get_query_var("workroom_id");
$item_id=$params;
?>
<div class="wrapper_collab">
	<div class="wrapper_collabinside">
		
		<?php /* Collaboration Room Info: Start */ ?>
		<?php include(collaboration_plugin_dir.'templates/frontend/frontend-workroom-info.php' ); ?>
		<?php /* Collaboration Room Info: End */ ?>

		<?php /* Collaboration Navigation & Messaging: Start */ ?>
		<div class="collabcolcontainer">

            <?php include(collaboration_plugin_dir.'templates/frontend/frontend-left-navs.php' ); ?>
            
            <?php /* Center Col Content: Start */ ?>
            <div class="collabcol centercol collabcontent">
				<div class="collabinside">
					
					<?php /* Collab Section (Team Members): Start */ ?>
					<div class="collabsec wrteammembers">
						<h2><span>Team Members</span></h2>
						
						<div class="collabfiles">
							
							<?php /* Add New Collaborators: Start */ ?>
                            <?php if(get_post_field( 'post_author', $params)==get_current_user_id()){ ?>
							<div class="collabattachandsubmit">
								<div class="collabadditem">
									<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members-invitation')).$params; ?>">Add New Collaborator</a>
									
									<div class="clear"></div>
								</div>
								<div class="collabaddmemberstext">
									Add additional users to collaborate with on your project.
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
                            <?php } ?>
							<?php /* Add New Collaborators: End */ ?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Team Members): End */ ?>
					
					<?php $data=get_teams_as_workroom($params); ?>
					
					<?php /* Collab Section (Team Members List): Start */ ?>
					<div class="collabsec teammembersoutput">
						<table class="collabtable" border="0" id="MEMBER_TABLE">
							<thead>
								<tr>
									<th class="messagesender">
										Name
									</th>
									<th class="messageoutput" width="30%">
										Role
									</th>
                                    <?php
									$id = get_post_field( 'post_author', $params );
									if($id==get_current_user_id()){
									?>
									<th class="messagedate"  width="20%">
										Actions
									</th>
                                    <?php
									}
									?>
								</tr>
							</thead>
							<tbody>
                            <?php
							$collaborators_index=0;
							foreach($data as $records){
								$author_id = $records->Collaborator_Id;
								$status=$records->Invitation_Status;
								$item_id=$records->Item_Id;
								$Alert_Id='';
								
								
								if($status!='Accepted' && $status!='Invited') continue 1;
								$collaborators_index++;
								$colspan=2;
								if($id==get_current_user_id()){
										$colspan=3;
								}
								
								?>
                                <tr class="odd first ">
									<td class="userinformation">
                                    	
                                            <div class="usermainname">
                                            	
                                                <?php echo get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id); ?>
                                                
                                            </div>
                                            <div class="usersusername">
                                            	<a href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $author_id; ?>" target="_blank">
                                                <?php 
                                                    echo get_the_author_meta('display_name', $author_id);
                                                ?>
                                                </a>
                                            </div>
                                        
									</td>
									<td class="usersrole">
										<?php
										$workroom_leader=get_post_field('post_author',$params);
										
										if($status=='Invited'){
											echo 'Invite - Pending';
										}elseif($status=='Accepted'){
											if($workroom_leader!=$author_id){
												echo 'Invited Collaborator';
											}else{
												echo 'Workroom Leader';	
											}
										}
										?>
									</td>
                                    <?php
									$id = get_post_field( 'post_author', $params );
									if($id==get_current_user_id()){
									?>
									<td class="userslistoptions ACTION">
										<?php
										$workroom_creater=get_post_field('post_author',$params);
										if($workroom_creater!=$author_id){
										?>
                                        <div class="wrlistitemactionsholder">
										<div class="fileslistoptionstxt wrlistitemactionstxt">
											Options
										</div>
										
										<ul class="fileslistoptionsitems writemactionslist">
											<li class="last">
												<a href="JavaScript:void(0)" 
													data-alert-id="<?php echo $Alert_Id; ?>" 
													data-collaborator-id="<?php echo $author_id; ?>" 
													data-workroom-id="<?php echo $params; ?>" 
													data-status="<?php echo ($status=='Invited')?'Cancel Invitation':'Delete Invitation'; ?>" 
													onClick="JavaScript:Edd.collaboration.TEAM_MEMBERS.EDIT_MEMBERS(this);">
												<?php
												if($status=='Invited'){
													echo 'Cancel Invitation';
												}elseif($status=='Accepted'){
													echo 'Delete';
												}
												
												?>
												</a>
											</li>
										</ul>
                                        </div>
                                        <?php
										}
										?>
									</td>
									<?php } ?>
								</tr>
                                <?php } ?>
							</tbody>
                            <tfoot>
								<tr>
									<td colspan="<?php echo $colspan; ?>"><?php echo $collaborators_index; ?> Collaborators</td>
								</tr>
							</tfoot>
						</table>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Team Members List): End */ ?>
					
					<div class="clear"></div>
				</div>
				
    			<div class="clear"></div>
             </div>
            <?php /* Center Col Content: End */ ?>
            
            <div class="clear"></div>
        </div>
        <?php /* Collaboration Navigation & Messaging: End */ ?>
        
        <div class="clear"></div>
    </div>
    
    <div class="clear"></div>
</div>

<div class="clear"></div> 
