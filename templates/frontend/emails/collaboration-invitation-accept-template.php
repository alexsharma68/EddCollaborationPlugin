<?php
/*
$Inv->Status 			= Will return status of Invitation
$Inv->Collaborator_Name = Will return name of collaborator
$Inv->Workroom_Name 	= Will return name of workroom
$Inv->Site_Name 		= Will return name of site
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
			<p>Hi <?php echo $Inv->Collaborator_Name; ?>,</p>

			<?php if($Inv->Status=='Accepted'){ ?>
			<p>You have successfully accepted the invitation to be a member of the workroom <b><?php echo $Inv->Workroom_Name; ?></b></p>
			<?php } ?>
			<?php if($Inv->Status=='Expired'){ ?>   
			<p>Your invitation to join the workroom <b><?php echo $Inv->Workroom_Name; ?></b> has expired.</p>
			<?php } ?>
			<?php if($Inv->Status==''){ ?>
			<p>Something went wrong - please try again.</p>
			<?php } ?>

			<p>Please click on the following link to go to the website: <a href="<?php echo $Inv->Site_Link; ?>">Here</a></p>

			<p>Thanks,<br/>
				<?php echo $Inv->Site_Name; ?></p>
			
			<p class="sadescription">ShareArts is an online platform for artists, authors and all creative individuals where they can list, collaborate, share, store their unfinished works and market finished products. Similar to iTunes or Amazon but with added collaboration features and specialized in arts like music, literature and design.</p>
		</div>
		
		<div class="saemailfooter">
			<p>Copyright &copy; <?php echo Date(Y); ?> <a href="http://www.sharearts.com" target="_blank">ShareArts</a>. All Rights Reserved.</p>
		</div>
		
	</div>
</div>