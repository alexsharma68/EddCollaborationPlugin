<?php
/*
$FileAlert->Collaborator_Name 	= Will return name of collaborator/artist
$FileAlert->Notification_From 	= Who posted the file
$FileAlert->Notification_Link 	= Will give link to visit file
$FileAlert->Workroom_Name 		= Will give you in which workroom file is posted
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
			<p>Hi <?php echo $FileAlert->Collaborator_Name; ?>,</p>

			<p><?php echo $FileAlert->Notification_From; ?> posted a file in the workroom <b><?php echo $FileAlert->Workroom_Name; ?></b></p>

			<p>Please click on the following link to see the file:<br />
				<?php echo $FileAlert->Notification_Link; ?></p>

			<p>File posted by: <?php echo $FileAlert->Notification_From; ?></p>

			<p>Regards,<br/>
				<?php echo $FileAlert->Notification_From; ?>,<br/>
				ShareArts</p>
			
			<p class="sadescription">ShareArts is an online platform for artists, authors and all creative individuals where they can list, collaborate, share, store their unfinished works and market finished products. Similar to iTunes or Amazon but with added collaboration features and specialized in arts like music, literature and design.</p>
		</div>
		
		<div class="saemailfooter">
			<p>Copyright &copy; <?php echo Date(Y); ?> <a href="http://www.sharearts.com" target="_blank">ShareArts</a>. All Rights Reserved.</p>
		</div>
		
	</div>
</div>