<?php
/*
$workroom_alert->collaborator_name 	= Will return name of collaborator/artist
$workroom_alert->site_admin 		= Will return admin name
$workroom_alert->workroom_problem 	= Will return user posted problem detail
$workroom_alert->workroom_name 		= Will give you in which workroom file is posted
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
			<p>Hi <?php echo $workroom_alert->site_admin; ?>,</p>

			<p><?php echo $workroom_alert->collaborator_name; ?> put the workroom <b><?php echo $workroom_alert->workroom_name; ?></b> in dispute. Please login to take further action.</p>
			
			<h3>Problem Details:</h3>
			
			<p><?php echo $workroom_alert->workroom_problem; ?></p>
            
			<p>Regards,<br />
				ShareArts</p>
			
			<p class="sadescription">ShareArts is an online platform for artists, authors and all creative individuals where they can list, collaborate, share, store their unfinished works and market finished products. Similar to iTunes or Amazon but with added collaboration features and specialized in arts like music, literature and design.</p>
		</div>
		
		<div class="saemailfooter">
			<p>Copyright &copy; <?php echo Date(Y); ?> <a href="http://www.sharearts.com" target="_blank">ShareArts</a>. All Rights Reserved.</p>
		</div>
		
	</div>
</div>