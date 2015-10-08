<?php
/*
You Have : 
$MessageAlert->Collaborator_Name 	= Will return name of collaborator/artist
$MessageAlert->Notification_From 	= Who posted the message
$MessageAlert->Notification_Link 	= Will give link to visit message
$MessageAlert->Workroom_Name 		= Will give you in which workroom file is posted
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
			<p>Hi <?php echo $MessageAlert->Collaborator_Name; ?>,</p>

			<p><?php echo $MessageAlert->Notification_From; ?> posted a message in the workroom <b><?php echo $MessageAlert->Workroom_Name; ?></b></p>

			<p>Please click on the following link to see the message:<br />
            "<?php echo $MessageAlert->Workroom_Message; ?>"
            
				<?php echo $MessageAlert->Notification_Link; ?></p>

			<p><b>Message posted by:</b> <?php echo $MessageAlert->Notification_From; ?></p>

			<p>Regards,<br/>
				<?php echo $MessageAlert->Notification_From; ?>,<br/>
				ShareArts</p>
			
			<p class="sadescription">ShareArts is an online platform for artists, authors and all creative individuals where they can list, collaborate, share, store their unfinished works and market finished products. Similar to iTunes or Amazon but with added collaboration features and specialized in arts like music, literature and design.</p>
		</div>
		
		<div class="saemailfooter">
			<p>Copyright &copy; <?php echo Date(Y); ?> <a href="http://www.sharearts.com" target="_blank">ShareArts</a>. All Rights Reserved.</p>
		</div>
		
	</div>
</div>