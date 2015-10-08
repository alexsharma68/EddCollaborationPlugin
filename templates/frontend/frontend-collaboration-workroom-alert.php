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
					
					<?php /* Collab Section (Files Output List): Start */ ?>
                    <form action="" method="post" id="Alert_List">
						<div class="collabsec filesoutput">
						<div class="collabadditem" style="float:right; width:auto;">
							<a href="javascript:void(0)" id="Delete_Alerts">
								Delete Selected Alerts
							</a>
							
							<div class="clear"></div>
						</div>
						<?php 
						$Alert_Id='';
						if(isset($_GET['Alert_Id'])){
							$Alert_Id=$_GET['Alert_Id'];
						}
						$posts=edd_get_alerts(get_current_user_id(),$Alert_Id);
						?>
							<table class="collabmessagetable collabtable" id="Alert_Grid_List" border="0">
								<thead>
									<tr>
										<th>Alert Descriptions</th>
										<th width="80">Action</th>
									</tr>
								</thead>
								<tfoot>
									<tr>
										<td colspan="2">Totals <span  id="Total_Alerts"><?php echo sizeof($posts); ?></span> alerts found.</td>
									</tr>
								</tfoot>
								<tbody>
									<?php
									foreach($posts as $post) {
										$Extra_Classes='';
										$Alerts_Id=get_post_meta($post->ID,'Edd_Alert_Id',true);
										
										if((isset($_GET['Alert_Id']) && $_GET['Alert_Id']==$Alerts_Id)){
											$Extra_Classes='active_alert';
											
											if(get_post_meta($post->ID,'Is_Alert_Read',true)==''){
												echo "<script>$(function(){ Edd.collaboration.Alerts.Read('".$post->ID."'); });</script>";
												update_post_meta($post->ID,'Is_Alert_Read','YES',false);
											}
										}
									?>
									<tr class="odd <?php echo $Extra_Classes; ?>" data-id="<?php echo $post->ID; ?>">
										<td class="fileinformation">
											<div class="fileinfofilelink">
												<a href="javascript:void(0);"><?php echo $post->post_content; ?></a>
											</div>
											<div class="fileinfoadditional">
												On <?php echo date_format(date_create($post->post_date),'M d, Y - H:i A'); ?> From 
												<a href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $post->post_author; ?>" target="_blank">
												<?php 
													$wp=new WP_User(get_post_meta($post->ID, 'Edd_Alert_From',true));
													echo $wp->display_name;
												?>
												</a>
											</div>
										</td>
										<td class="fileslistoptions">
											<input type="checkbox" value="<?php echo $post->ID; ?>" name="Alert_Checkboxs[]" >
										</td>
									</tr>
									<?php } ?>
								</tbody>
							</table>
							
							<div class="clear"></div>
						</div>
                    </form>
					<?php /* Collab Section (Files Output List): End */ ?>
					
					<?php /* Pagination: Start */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* NOTE: If only one page of comments, don't show pagination */ ?>
					<?php /* ********************************************************* */ ?>
					
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
