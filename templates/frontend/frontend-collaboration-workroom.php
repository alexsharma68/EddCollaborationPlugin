<?php
$params=get_query_var("workroom_id");
?>
<link rel="stylesheet" href="<?php echo collaboration_plugin_url.'assets/css/jquery.fileupload.css'; ?>">

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.ui.widget.js'; ?>"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.iframe-transport.js'; ?>"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.fileupload.js'; ?>"></script>
<script>
	/*jslint unparam: true */
	/*global window, $ */
	var total=0;
	
	function getFileExtension(filename) {
		var ext = /^.+\.([^.]+)$/.exec(filename);
		return ext == null ? "" : ext[1];
	}
	
	jQuery(function ($) {
		'use strict';
		
		// Change this to the location of your server-side upload handler:
		var url ='<?php echo collaboration_plugin_url . 'attachments/'; ?>';
		
		$('#fileupload').fileupload({
			url: url,
			add: function(e, data) {
				//console.log(getFileExtension(data.originalFiles[0]['name'])+ " -|- "+ data.originalFiles[0]['type'].length);
				<?php
				$a		= get_option('allowed_files_type');
				$b		= explode("|", $a);
				$ext	= implode(', ', $b);
			   ?>
				var uploadErrors = [];
				var acceptFileTypes =/^(<?php echo get_option('allowed_files_type');?>)$/;
				
				if(!acceptFileTypes.test(getFileExtension(data.originalFiles[0]['name']))) {
					uploadErrors.push('The following extensions are accepted (<?php echo $ext; ?>) along with a file size <?php echo get_option('max_upload_size'); ?> and below.');
				}
				
				var calc=((data.originalFiles[0]['size']/1024)/1024);
				//alert(calc);
				
				if(calc > parseInt('<?php echo get_option('max_upload_size'); ?>')) {
					uploadErrors.push('File size is too large. The allowed size is <?php echo get_option('max_upload_size'); ?> or smaller.');
				}
				
				if(total>parseInt('<?php echo delete_option('file_limitation_to_upload'); ?>')){
					uploadErrors.push('You can\'t upload more than 2 files at a time.');
				}
				
				if(uploadErrors.length > 0) {
					alert(uploadErrors.join("\n"));
				} else {
					data.submit();
				}
				
			},
			maxNumberOfFiles			: parseInt('<?php echo delete_option('file_limitation_to_upload'); ?>'),
			maxFileSize					: parseInt('<?php echo get_option('max_upload_size'); ?>'),
			dataType					: 'json',
			done						: function (e, data) {
				total++;
				// if(total<=2){
				$.each(data.result.files, function (index, file) {
					$('<input name="attachments[]" type="hidden" value="'+file.name+'">').appendTo('#files');
					$('</p>').text(file.name).appendTo('#files');
				});
				// }else{
					//alert('You exceed upload limit'); 
				// }
			},
			process: function (e, data) {
				// alert(progress+ " All");
			},
			progressall: function (e, data) {  
				var progress = parseInt(data.loaded / data.total * 100, 10);
				
				$('#progress .progress-bar').css(
					'width',
					progress + '%'
				);
				
				$(".collabstoragestats span").html(Edd.collaboration.MajerMent.formatSizeUnits(data.loaded)+" of "+Edd.collaboration.MajerMent.formatSizeUnits(data.total)+" Uploaded");
			}
		}).prop('disabled', !$.support.fileInput)
			.parent().addClass($.support.fileInput ? undefined : 'disabled');
	});
</script>
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
					<div class="collabsec wrmessaging">
						<h2><span>Messages</span></h2>
						
						<form action="" method="post" id="MESSAGE_FORMS">
							<div class="collabmessaging">
								
								<?php /* Write Message: Start */ ?>
								<div class="collabwritemessage">
									<textarea id="collabmessagetxtfld" name="POST_MESSAGE" class="text POST_MESSAGE" maxlength="<?php echo get_option('character_limit_for_message'); ?>"></textarea>
                                    <label>Only '<?php echo get_option('character_limit_for_message'); ?>' allowed characters.</label>
									<input type="hidden" name="Workroom_Id" value="<?php echo $params; ?>">
									
									<div class="clear"></div>
								</div>
								<?php /* Write Message: End */ ?>
								
								<?php /* Attach and Submit: Start */ ?>
								<div class="collabattachandsubmit">
									<div class="collabadditem">
										<a href="JavaScript:void(0)" class="attachment" id="ATTACHMENTS">
											<label for="fileupload" style="margin: 0 !important; padding: 0 !important;">+ Add attachment</label>
											<input style="display: none;" id="fileupload" type="file" name="files[]" multiple />
										</a>
										
										<div class="clear"></div>
									</div>
									
									<div class="collabmessagesubmit">
										<input type="button" class="button" id="POST_MESSAGE_BUTTON" value="Submit Message" />
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
								
								<?php // Progress Bar: Start ?>
								<div id="progress" class="progress">
									<div class="progress-bar progress-bar-success"></div>

									<div class="clear"></div>
								</div>
								
								<div id="files" class="files jlitemfiles"></div>
								<?php /* Attach and Submit: End */ ?>
								
								<div class="clear"></div>
							</div>
						</form>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Message Submission): End */ ?>
					
					<?php /* Pagination: Start */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* NOTE: If only one page of comments, don't show pagination */ ?>
					<?php /* ********************************************************* */ ?>
					<div class="collabsec wrpagination">
						<div class="collabpagination toppagination">
							<div class="collabpageroptions">
								
								
								<div class="clear"></div>
							</div>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
					<?php /* Pagination: End */ ?>
					
					<style type="text/css">
					.attachments > li > a,
					.attachments > li > a:hover {
						color: #333;
						font-size: 15px;
						font-weight: 600;
						}
					</style>
					
					<?php /* Collab Section (Message Output List): Start */ ?>
					<div class="collabsec messagingoutput">
						<table class="collabmessagetable collabtable" id="MESSAGE_TABLE" border="0">
							<thead>
								<tr>
									<th class="messagesender" width="25%">
										Sender
									</th>
									<th class="messageoutput">
										Message
									</th>
									<th class="messagedate" width="10%">
										Date
									</th>
								</tr>
							</thead>
							<tfoot>
								<tr>
									<td colspan="3">All times are in EST (UTC-05:00)</td>
								</tr>
							</tfoot>
							<tbody>
								<?php
								$Alert_Id='';
								
								if(isset($_GET['Alert_Id'])){
									$Alert_Id=$_GET['Alert_Id'];
								}
								
								$posts =get_messages($params,$Alert_Id);
								
								foreach($posts as $post) {
									$Extra_Classes='';
									$Alerts_Id=get_post_meta($post->ID,'Edd_Alert_Id',true);
									if((isset($_GET['Alert_Id']) && $_GET['Alert_Id']==$Alerts_Id)){
										$Extra_Classes='active_alert';
										$Alert_Post_Id=get_alert_id_by_meta_value($Alerts_Id);
										if(get_post_meta($Alert_Post_Id,'Is_Alert_Read',true)==''){
										echo "<script>$(function(){ Edd.collaboration.Alerts.Read('".$Alert_Post_Id."'); });</script>";
										update_post_meta($Alert_Post_Id,'Is_Alert_Read','YES',false);
										}
									}
									?>
									<tr class="odd <?php echo $Extra_Classes; ?>">
										<td class="messagesender">
                                        	<a target="_blank" href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $post->post_author; ?>">
												<?php 
                                                    $wp=new WP_User($post->post_author);
                                                    $wps=get_userdata($post->post_author);
                                                    echo $wps->display_name;
                                                ?>
                                            </a>
										</td>
										<td class="messageoutput">
											<div class="messageoutput_inside">
												<div class="reportmessage">
													<a href="#">Report Message</a>
												</div>
												<?php 
												echo nl2br($post->post_content);
												$attachs=get_post_meta($post->ID, '_wp_collaboration_attachments',true);
												?>
												
												<div class="clear"></div>
												
												<?php 
												if(is_array($attachs) && sizeof($attachs)>0){
													?>
												   
													<ul class="attachments">
														<li>
															<a href="JavaScript:void(0)">Attachments</a>
															
															<ul class="attached-list">
																<?php foreach($attachs as $atts){ ?>
																<li>
																	<a href="javascript:void(0);" 
																		data-file-id='<?php echo $post->ID; ?>' 
																		data-file-name="<?php echo $atts; ?>" 
																		onClick="JavaScript:Edd.collaboration.DASHBOARD.DOWNLOAD_ATTACHMENTS(this);">
																		<?php echo $atts; ?>
																	</a>
																</li>
																<?php } ?>
															</ul>
														</li>
													</ul>
													<?php
												}
												?>
											</div>
										</td>
										<td class="messagedate">
											<div>
												<?php echo date_format(date_create($post->post_date),'Y/m/d'); ?>
											</div>
											<div>
												<?php echo date_format(date_create($post->post_date),'H:i A'); ?>
											</div>
										</td>
									</tr>
									<?php
								}
								?>
							</tbody>
						</table>
						
						<div class="clear"></div>
					</div>
					<?php /* Collab Section (Message Output List): End */ ?>
					
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
