<?php
/*
You Have : 
$InvAlerts->Collaborator_Name 		= Will return name of collaborator/artist
$InvAlerts->Invitation_Sender 		= Who sent invitation
$InvAlerts->Invitation_Accept_Link 	= Link to accept invitation
$InvAlerts->Workroom_Name 			= Will give you in which workroom file is posted
*/
?>

<div class="saemail">
	<div class="saemailframe">
		
		<div class="saemailheader">
			<a href="http://www.sharearts.com" target="_blank">
				<img src="http://www.sharearts.com/sa_email_logo.png" alt="ShareArts Logo" />
			</a>
		</div>
		
		<div class="saemailbody">
			<p>Hi <?php echo $InvAlerts->Collaborator_Name; ?>,</p>

			<p>You have received an invitation to collaborate in the workroom <b><?php echo $InvAlerts->Workroom_Name; ?></b>.</p>

			<p>Please click on the following link to accept the invitation:<br />
				<?php echo $InvAlerts->Invitation_Accept_Link; ?></p>

			<p><b>Invitation sent by:</b> <?php echo $InvAlerts->Invitation_Sender; ?></p>

			<p>Regards,<br />
				<?php echo $InvAlerts->Invitation_Sender; ?>,<br/>
				ShareArts</p>
			
			<p class="sadescription">ShareArts is an online platform for artists, authors and all creative individuals where they can list, collaborate, share, store their unfinished works and market finished products. Similar to iTunes or Amazon but with added collaboration features and specialized in arts like music, literature and design.</p>
		</div>
		
		<div class="saemailfooter">
			<p>Copyright &copy; <?php echo Date(Y); ?> <a href="http://www.sharearts.com" target="_blank">ShareArts</a>. All Rights Reserved.</p>
		</div>
		
	</div>
</div>