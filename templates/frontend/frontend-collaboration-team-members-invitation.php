<?php
$params=get_query_var("workroom_id");
$item_id=get_item_id_by_workroom_id($params);
?>
<div class="wrapper_collab">
	<div class="wrapper_collabinside">
		
		<?php /* Collaboration Room Info: Start */ ?>
		<?php include(collaboration_plugin_dir.'templates/frontend/frontend-workroom-info.php' ); ?>
		<?php /* Collaboration Room Info: End */ ?>

		<?php /* Collaboration Navigation & Messaging: Start */ ?>
		<div class="collabcolcontainer">

            <?php include(collaboration_plugin_dir.'templates/frontend/frontend-left-navs.php'); ?>
            
            <?php /* Center Col Content: Start */ ?>
            <div class="collabcol centercol collabcontent">
				<div class="collabinside">
					
					<?php /* Collab Section (Team Members): Start */ ?>
					<div class="collabsec wrteammembers">
						<h2><span>Team Members</span></h2>
						
						<div class="collabfiles">
							
							<?php /* Add New Collaborators: Start */ ?>
							<div class="collabattachandsubmit">
								<div class="collabadditem">
									<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members')).$params; ?>">
										Back to Collaborator List
									</a>
									
									<div class="clear"></div>
								</div>
								
								<div class="collabaddmemberstext">
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							<?php /* Add New Collaborators: End */ ?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Team Members): End */ ?>
					
					<?php /* Collab Section (Team Members List): Start */ ?>
					<div class="collabsec teammembersoutput">
						<form name="MEMBER_INVITE_FORM" id="MEMBER_INVITE_FORM" action="" method="post">
							<?php ?>
							<input type="hidden" name="ID" value="<?php echo $ID; ?>" />
							
							<table class="collabtable" id="MEMBER_INVITE_TABLE" border="0">
								<thead>
									<tr>
										<!--
										<th class="messagesender" width="10%">
											<input type="checkbox" name="all[]" id="all">
										</th>
										-->
										<th class="messagesender">
											Name
										</th>
										<th class="messagedate" width="15%">
											Actions
										</th>
									</tr>
								</thead>
								<tbody>
									<?php
									$collaborators_index=0;
									$rows=get_teams_as_workroom($params);
									
									if(is_array($rows) && sizeof($rows)>0){
										foreach($rows as $records){
										  $author_id = $records->Collaborator_Id;
											$status=$records->Invitation_Status;
											$item_id=$records->Item_Id;
											if($status=='Accepted' || $status=='Invited') continue 1;
											if($author_id==get_current_user_id($records))continue 1;
											$collaborators_index++;
										?>
										<tr>
											<td class="userinformation">
												<div class="usermainname">
													<?php echo get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id); ?>
												</div>
												<div class="usersusername">
                                                	<a href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $author_id; ?>" target="_blank">
														<?php echo get_the_author_meta('display_name', $author_id); ?>
                                                    </a>
												</div>
											</td>
											<td class="userslistoptions">
                                             	<div class="wrlistitemactionsholder">
                                                    <div class="fileslistoptionstxt wrlistitemactionstxt">
                                                                Options
                                                    </div>
                                                    <ul class="fileslistoptionsitems writemactionslist">
                                                    	<li class="first">
                                                            <a type="button" 
                                                                data-workroom-id='<?php echo $params; ?>' 
                                                                data-collaborator-id='<?php echo $author_id; ?>' 
                                                                data-action="Invited" 
                                                                class="INVITE_TEAM">
                                                                Invite to join
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
											</td>
										</tr>
										<?php
										}
									}
									?>
								</tbody>
								<tfoot>
									<tr>
										<td colspan="2"><span id="count_collaborators"><?php echo $collaborators_index; ?></span> Collaborators</td>
									</tr>
								</tfoot>
							</table>
						</form>
						
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
