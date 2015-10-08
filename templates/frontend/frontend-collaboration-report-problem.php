<?php
$Workroom_Id=get_query_var("workroom_id");
$params=$Workroom_Id;
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
					
					<?php /* Collab Section (Message Submission): Start */ ?>
					<div class="collabsec wrfiles">
						<h2><span>Report a Problem</span></h2>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Message Submission): End */ ?>
					
					<?php /* Pagination: Start */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* NOTE: If only one page of comments, don't show pagination */ ?>
					<?php /* ********************************************************* */ ?>
					
					<?php /* Pagination: End */ ?>
					
					<?php /* Collab Section (Files Output List): Start */ ?>
					<form action="" method="post">
						<input name="Workroom_Id" type="hidden" value="<?php echo $Workroom_Id; ?>" id="Workroom_Id">
						
						<div class="newworkroomform">
							<div class="newworkroomform_inside">
								
								<div class="newworkroommessage">
									<div id="GLOBAL_MESSAGE_TOP"></div>
								</div>
								
								<div class="workroomformrow odd first">
									<label for="Problem_Title">Title</label>
									<div class="workroomformfld">
										<input type="text" name="Problem_Title" id="Problem_Title" />
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<div class="workroomformrow even">
									<label for="Problem_Reason">Reason</label>
									<div class="workroomformfld">
										<textarea id="Problem_Reason" name="Problem_Reason"></textarea>
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<div class="workroomformrow even last">
									<div class="workroomformfld">
										<button type="button" class="sabutton" onClick="Edd.collaboration.Report_Problem.Submit_Problem()">
											Submit
										</button>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<div class="clear"></div>
							</div>
							
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
