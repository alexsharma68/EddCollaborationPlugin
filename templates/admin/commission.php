
<div class="wrap">
	<?php include('tab.php'); ?>
    <?php
$item_id=get_main_item_id_by_workroom_id($params);
?>
    
    <div id="nav-menus-frame">
                    
                    <div id="menu-management-liquid" style="min-width:100%">
                        <div id="menu-management">
                            
                                <div class="menu-edit ">
                                    <div id="side-sortables" class="accordion-container">
                                        <ul class="outer-border">
                                                    <li class="control-section accordion-section  open add-page" id="add-page">
                                                        <h3 class="accordion-section-title" tabindex="0" style="background-image:none;">
                                                            Commission Proposals
                                                        </h3>
                                                        <div class="accordion-section-content">
                                                            <div id="post-body">
                                                                <div id="post-body-content">
                                                                    
                
                
                

						

					<?php /* Collab Section (Commission Proposals): End */ ?>
					
					<?php /* Collab Section (Commission Proposals List): Start */ ?>
					
						<p>Each user can specify their commission percentage below based on the agreement between all parties. The commission must amount to 100%, and each user can enter only their own commission percentage. The commission entered is the percentage a user will get from each sale, minus the ShareArts commission fee.</p>
						
						
						
						<h3>Commission Percentages for Each User</h3>
						<div id="GLOBAL_MESSAGE_TOP" style="display:none">
                            
                        </div>
                        <?php
						$repost='';
						$url=add_query_arg( array('task'=>'new-product','wid'=>$params),get_permalink(get_page_by_path('fes-vendor-dashboard')));
						if((get_post_meta($params,'Final_Approved',true)=='YES') && (get_post_field('post_author',$params)==get_current_user_id())){
							$repost='Click <a href="#" class="button" id="repost">here</a> to repost product.';
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
						<table>
									
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
									<tr>
										<td>
                                        <b><?php echo get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id); ?></b> Commission Percentage
                                        <div class="clear"></div>
										
													<input type="text" <?php echo $disabled; ?> 
														data-author-id="<?php echo $author_id; ?>" 
														id="COMMISSION_<?php echo $author_id; ?>" 
														class="text" 
														value="<?php echo get_user_meta($author_id,'download-'.$params,true); ?>" 
														placeholder="Enter percentage" />
													
													<div class="clear"></div>
												
													<input type="button" <?php echo $disabled; ?> 
														data-author-id="<?php echo $author_id; ?>" 
														data-workroom-id='<?php echo $params; ?>' 
														class="sabutton button" 
														value="Update" 
														onClick="JavaScript:Edd.collaboration.COMMISSION.Update_Commission(this)" />
													
													<div class="clear"></div>
                                                    <div class="confappwrpr">
                                                    	<input type="checkbox" data-author-id="<?php echo $author_id; ?>" <?php echo get_user_meta($author_id,'download-'.$params.'-approved',true)=='YES'?'checked="checked"':''; ?> style="float:left;" <?php echo $disabled; ?> data-workroom-id='<?php echo $params; ?>' id="approve-<?php echo $author_id; ?>" class="checkbox" onClick="JavaScript:Edd.collaboration.COMMISSION.Approve_User_Commission(this)" />
                                                    	<span for="approval1"><label style="float:left;" for="approve-<?php echo $author_id; ?>">Approve Commission?</label></span>
                                                        <div class="clear"></div>
                                                    </div>
												<div class="clear"></div>
								
											Only <b><?php echo get_the_author_meta('display_name', $author_id); ?></b> can enter their agreed commission percentage here.
										<div class="clear"></div>
										
										<div class="collabcommfldnotice success" style="display:none; color:green;" id="MESSAGE-<?php echo $author_id; ?>">
											You've successfully updated your commission.
										</div>
										
										<div class="clear"></div>
									</td>
                                    </tr>
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
									<tr>
                                            <td>
                                                <h3>Approve Commissions</h3>
                                                
                                                <p>The commission rates above are final once the 'Lead Collaborator' has approved them. Only the Lead Collaborator can approve the commissions, by clicking the 'Approve Commissions' button below. The Approve Commissions button will become clickable once all user's have entered their agreed commission (the combined commissions must total 100%) as well as each user having checked the 'Approve Commission' checkbox (becomes available once all commissions have been entered and equal 100%).</p>
                                                
                                                <p>Once approval has been made, the commission rates are final and cannot be changed.</p>
                                                
                                                <div class="clear"></div>
                                                
                                                <?php /* Add a captcha, as well as prompt the user 'Are you sure you want to Approve the above commissions?' */ ?>
                                               <input data-workroom-id='<?php echo $params; ?>' 
												type="button" <?php echo $disabled; ?> 
												class="sabutton button" value="Approve Commissions" 
												data-control-author="<?php echo get_post_field('post_author',$params); ?>" 
												onClick="JavaScript:Edd.collaboration.COMMISSION.Approve_Commission(this)" 
												style="float: left;" />
                                                
                                                <div class="clear"></div>
                                            </td>
										
                                    </tr>
                                    </table>
                                    
                                    
   </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                        </ul>
                                    </div> 
                                </div>
                        </div>
                    </div>

                </div>                                 
                                    
                                    
                                    
                                    
                                    </div>