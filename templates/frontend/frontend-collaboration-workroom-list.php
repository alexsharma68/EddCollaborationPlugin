<div class="wrapper_collab">
    <div class="collabadditem">
        <a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/add-workroom')); ?>" class="sabutton">Add New Workroom</a>
		
        <div class="clear"></div>
    </div>
	
    <div class="clear"></div>
	
	<div class="wrapper_collabinside">
		<div id="GLOBAL_MESSAGE_TOP"></div>
		
		<?php /* Workroom List Info: Start */ ?>
		<div class="workroomlistcontrols">
			<div class="collabsec wrworkroomlistcontrols">
				
				<?php /* Workroom Status List: Start */ ?>
				<div class="wrstatuslist">
					<div class="wrstatuslistlabel">
						<span class="wrstatuslabelbase">Workroom Status:</span>
						<span class="wrstatusactivelabel">All</span>
						<span class="srstatuslabelarrow"></span>
					</div>
					
					<ul class="wrlistcontroldropdown">
						<li class="wrslistitem wrlshowall active" data-action='all'>All</li>
						<li class="wrslistitem wrlshowinprogress" data-action='in-progress'>In Progress</li>
						<li class="wrslistitem wrlshowcomplete" data-action='complete'>Complete</li>
						<li class="wrlistseparator"><span></span></li>
						<li class="wrslistitem wrlshowcomplete" data-action='in-dispute'>In Dispute</li>
					</ul>
					
					<div class="clear"></div>
				</div>
				<?php /* Workroom Status List: End */ ?>
				
				<?php /* Workroom List Search: Start */ ?>
				<div class="wrlistsearch">
					<div class="wrlistsearch_inside">
						<input type="text" id="EDD_FILTER_WORKROOM" class="text wrsearchfld" value="" placeholder="Search Workroom List..." />
						<a href="JavaScript:void(0)" class="wrlistsearchbtn" id="WOEKROOM_SEARCH">
							<span></span>
						</a>
						
						<div class="clear"></div>
					</div>
					
					<div class="clear"></div>
				</div>
				<?php /* Workroom List Search: end */ ?>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		<?php /* Workroom List Info: Start */ ?>
		
		<?php /* Collaboration Workroom List: Start */ ?>
		<div class="collabcolcontainer">
			
			<?php /* Workroom List Col Content: Start */ ?>
			<div class="collabcol workroomlistcol">
            
				<div class="collabinside">
					<style type="text/css">
					.filterMatches {
						color: #f00;
						}
					</style>
					
					<?php /* Collab Section (Workspace List): Start */
					$arg=array(
						'author'=>get_current_user_id(),
						'collaboration_status'=>'all'
					);
					$workrooms=get_workroom($arg);
					?>
					<div class="collabsec workspacelistoutput">
						<table class="collabtable" border="0" id="WORKROOM_TABLE">
                            <thead style="display:none;">
                                <th>This is a hidden table head</th>
                                <th>This is a hidden table head</th>
                            </thead>
                            
							<tbody>
                            	<?php
								$workrooms_numb=0;
								$workrooms_list=array();
								if(sizeof($workrooms)>0){
									foreach($workrooms as $workroom){
										$params=$workroom->ID;
										$workroom_leader=get_post_field('post_author',$params);
										if(in_array($params,$workrooms_list)) continue 1;
										$workrooms_list[]=$params;
										$workrooms_numb++;
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
													<a href="javascript:void(0);">
														<?php echo ($workroom_leader==get_current_user_id())?'You':get_the_author_meta('display_name', $workroom_leader); ?>
													</a>
												</div>
											</td>
											<td class="wrlistitemactions">
												<div class="wrlistitemactionsholder">
													<div class="wrlistitemactionstxt">
														Actions
													</div>
													<ul class="writemactionslist">
														<li class="first">
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/message')).$params; ?>">
																Messages
															</a>
														</li>
														<li>
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/files')).$params; ?>">
																Files
															</a>
														</li>
														<li>
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members')).$params; ?>">
																Team Members
															</a>
														</li>
														<li>
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-commission')).$params; ?>">
																Commission
															</a>
														</li>
														<li>
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$params; ?>">
																Alerts
															</a>
														</li>
														<li>
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/report-problem')).$params; ?>">
																Report a Problem
															</a>
														</li>
														<?php if(get_post_field('post_author',$params)==get_current_user_id()){ ?>
														<li class="last">
															<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/add-workroom')).$params; ?>">
																Edit Workroom
															</a>
														</li>
														<?php } if(get_post_field('post_author',$params)==get_current_user_id()){ ?>
														<li>
															<a href="JavaScript:void(0)" onClick="Edd.collaboration.Workroom.Delete('<?php echo $params; ?>')">
																Delete Workroom
															</a>
														</li>
														<?php } ?>
													</ul>
												</div>
											</td>
										</tr>
										<?php
									} 
								}else{
								?>
								<tr class="odd first inprogress">
									<td class="workrooominformation">No Item Found</td><td></td>
								</tr>
								<?php } ?>
							</tbody>
                            <tfoot>
								<tr>
									<td colspan="2">
										<span id="Total_Workroom"><?php echo $workrooms_numb; ?></span> 
										Workrooms
									</td>
								</tr>
							</tfoot>
						</table>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Workspace List): End */ ?>
					
					<?php /* Pagination: Start */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* NOTE: If only one page of comments, don't show pagination */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* Pagination: End */ ?>
					
					<div class="clear"></div>
				</div>
				
				<div class="clear"></div>
			</div>
			<?php /* Workroom List Col Content: End */ ?>
			
			<div class="clear"></div>
		</div>
		<?php /* Collaboration Workroom List: End */ ?>
		
		<div class="clear"></div>
	</div>
    
    <div class="clear"></div>
</div>

<div class="clear"></div>
