<?php
$params=get_query_var("workroom_id");
$item_id=get_main_item_id_by_workroom_id($params);
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
					
					<?php /* Collab Section (Commission Proposals): Start */ ?>
					<div class="collabsec wrcommissionprop">
						<h2><span>Commission Proposals</span></h2>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Commission Proposals): End */ ?>
					
					<?php /* Collab Section (Commission Proposals List): Start */ ?>
					<div class="collabsec commissionpropoutput">
						
						<p>Each user can specify their commission percentage below based on the agreement between all parties. The commission must amount to 100%, and each user can enter only their own commission percentage. The commission entered is the percentage a user will get from each sale, minus the ShareArts commission fee.</p>
						
						<p>If there are any issues with agreeing upon a commission between all parties, please open a support request by <a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/report-problem')).$params; ?>">clicking here</a> or clicking 'Report a Problem' under 'Workroom'. Any user may open a support request at any time.</p>
						
						<h3>Commission Percentages for Each User</h3>
						
						<div id="GLOBAL_MESSAGE_TOP"></div>
                        
						<?php $url=add_query_arg( array('task'=>'new-product','wid'=>$params),get_permalink(get_page_by_path('fes-vendor-dashboard'))); ?>
                        
						<?php
						$repost='';
						if((get_post_meta($params,'Final_Approved',true)=='YES') && (get_post_field('post_author',$params)==get_current_user_id())){
							$repost='Click <a href="'.$url.'" class="button" id="repost">here</a> to repost product.';
						}
						
                        if(get_post_meta($params,'Final_Approved',true)=='YES'){
							?>
                            <script type="text/javascript">
							$(function(){
							Edd.collaboration.SetErrorMessage({'Message':'Commission is already approved by all users. <?php echo $repost; ?>','Color':'Green','Status':'success','Fade':false});
							})
							</script>
                            <?php
						}
                        ?>
                        
						<?php /* Collab Form: Start */ ?>
						<div class="collabsecform">
							<form id="" class="" method="" action="">
								<div class="collabsecform_inside">
									
								<?php /* Username1 Commission Percentage: Start */ 
								$rows=get_teams_as_workroom($params);
								$user_index=0;
								$show=true;
								$total_amounts_entered=0;
							  	foreach($rows as $records){
								  	$author_id = $records->Collaborator_Id;
									$status=$records->Invitation_Status;
									$main_item_id=$records->Main_Item_Id;
									
									if($status!='Accepted') continue 1;
									
									$user_index++;
									$disabled=$author_id!=get_current_user_id()?'disabled':'';
									$total_amounts_entered=$total_amounts_entered+get_user_meta($author_id,'download-'.$params,true);
									if(get_user_meta($author_id,'download-'.$params,true)==''){
										$show=false;
									}
									if(get_post_meta($params,'Final_Approved',true)=='YES'){
										$disabled='disabled';
									}
									if(get_user_meta($author_id,'download-'.$params.'-approved',true)=='YES'){
								     $disabled='disabled';
									}
													
									?>
									<div class="collabformrow">
										<label for="usernamepercentage1">
											<b><?php echo get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id); ?></b> Commission Percentage
										</label>
										
										<div class="collabformrowfield">
											
											<div class="collabdblflds">
												<div class="fldwrpr">
													<input type="text" <?php echo $disabled; ?> 
														data-author-id="<?php echo $author_id; ?>" 
														id="COMMISSION_<?php echo $author_id; ?>" 
														class="text" 
														value="<?php echo get_user_meta($author_id,'download-'.$params,true); ?>" 
														placeholder="Enter percentage" />
													
													<div class="clear"></div>
												</div>
												<div class="bttnwrpr">
                                                	
													<input type="button" <?php echo $disabled; ?> 
														data-author-id="<?php echo $author_id; ?>" 
														data-workroom-id='<?php echo $params; ?>' 
														class="sabutton button" 
														value="Update" 
														onClick="JavaScript:Edd.collaboration.COMMISSION.Update_Commission(this)" />
													
													<div class="clear"></div>
												</div>
												
												<div class="clear"></div>
											</div>
											
											<?php /* Only show after all commissions entered: Start */ ?>
											<div class="confappwrpr">
												<input type="checkbox" 
													data-author-id="<?php echo $author_id; ?>" <?php echo get_user_meta($author_id,'download-'.$params.'-approved',true)=='YES'?'checked="checked"':''; ?> 
													style="float: left;" <?php echo $disabled; ?> 
													data-workroom-id='<?php echo $params; ?>' 
													id="approve-<?php echo $author_id; ?>" 
													class="checkbox" 
													onClick="JavaScript:Edd.collaboration.COMMISSION.Approve_User_Commission(this)" />
												<span for="approval1">
													<label style="float: left;" for="approve-<?php echo $author_id; ?>">Approve Commission?</label>
												</span>
												
												<div class="clear"></div>
											</div>
											<?php /* NOTE: Each user has to mark their own commission as approved */ ?>
											<?php /* Only show after all commissions entered: End */ ?>
											
											<div class="clear"></div>
										</div>
										
										<div class="collabfldsubtxt">
											Only <b><?php echo get_the_author_meta('display_name', $author_id); ?></b> can enter their agreed commission percentage here.
										</div>
										
										<div class="collabcommfldnotice success" style="display:none; color:green;" id="MESSAGE-<?php echo $author_id; ?>">
											You've successfully updated your commission.
										</div>
										
										<div class="clear"></div>
									</div>
									<?php /* Username1 Commission Percentage: End */ 
								}
								$disabled='';
								if((get_post_meta($params,'Final_Approved',true)=='YES') || (get_post_field('post_author',$params)!=get_current_user_id())){
									$disabled='disabled';
								}
								?>
								<?php
									if($user_index==0){
										$disabled='disabled';
										?>
										<div id="GLOBAL_MESSAGE" style="color: green; background-color: #ffc; padding: 10px; border: 1px solid #999; margin-top: 5px; margin-bottom: 10px;">
											No users for commission collaboration - Please invite all users to process Commission.
										</div>
										<?php
									}
									
									if($total_amounts_entered=='100' && $show==true){
										?>
                                        <script type="text/javascript">
										$(function(){
										Edd.collaboration.Show_Approve_Check_Boxes();
										})
										</script>
                                        <?php
									}
								?>
									
									<?php /* Approve Commission Percentage: Start */ ?>
									<div class="collabformrow approval">
										<h3>Approve Commissions</h3>
										
										<p>The commission rates above are final once the 'Lead Collaborator' has approved them. Only the Lead Collaborator can approve the commissions, by clicking the 'Approve Commissions' button below. The Approve Commissions button will become clickable once all user's have entered their agreed commission (the combined commissions must total 100%) as well as each user having checked the 'Approve Commission' checkbox (becomes available once all commissions have been entered and equal 100%).</p>
										
										<p>Once approval has been made, the commission rates are final and cannot be changed.</p>
										
										<div class="collabformrowfield">
											
											<?php /* Add a captcha, as well as prompt the user 'Are you sure you want to Approve the above commissions?' */ ?>
											<input data-workroom-id='<?php echo $params; ?>'  
												type="button" <?php echo $disabled; ?> 
												class="sabutton button" value="Approve Commissions" 
												data-control-author="<?php echo get_post_field('post_author',$params); ?>" 
												onClick="JavaScript:Edd.collaboration.COMMISSION.Approve_Commission(this)" 
												style="float: left;" />
											
											<div class="clear"></div>
										</div>
										
										<div class="clear"></div>
									</div>
									<?php /* Username4 Commission Percentage: End */ ?>
									
									<div class="clear"></div>
								</div>
							</form>
							
							<div class="clear"></div>
						</div>
						<?php /* Collab Form: Start */ ?>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Commission Proposals List): End */ ?>
					
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
