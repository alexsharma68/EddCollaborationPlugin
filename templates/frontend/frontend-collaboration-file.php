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
						<h2><span>Files</span></h2>
						
						<div class="collabfiles">
							
							<?php /* Attach and Submit: Start */ ?>
							<div class="collabattachandsubmit">
								<div class="collabadditem">
									<a href="JavaScript:void(0)">
										<label for="fileupload" style="margin: 0 !important; padding: 0 !important;">+ Upload New File</label>
										<input style="display: none;" id="fileupload" type="file" name="files[]" multiple />
									</a>
									
									<div class="clear"></div>
								</div>
								
								<?php 
								$Wrom=get_workroom_space_details($params);
								?>
                                <div class="collabstorageused">
									<div class="collabstoragecounter">
										<div class="collabstoragetotal"></div>
										<div class="collabstoragecurrent" id="workroom_space_progress" style="width:<?php echo $Wrom['UsedSpaceWithPercentage']; ?>%;"></div>
									</div>
									
									<div class="collabstoragestats" id="workroom_space_detail">
										<?php echo $Wrom['UsedSpaceWithPercentage']; ?>% of Storage Used | <?php echo $Wrom['UsedSpaceWithUnit']; ?> of <?php echo $Wrom['TotalSpace']; ?> Used
										
										<div class="clear"></div>
									</div>
									
									<div class="clear"></div>
								</div>
                                
								<div class="clear"></div>
							</div>
							<?php /* Attach and Submit: End */ ?>
							
							<div class="clear"></div>
						</div>
						
						<div class="clear"></div>
					</div>
                    
                    <!-- Progress Bar -->
                    <div class="collabstorageused" style="display:none;">
                        <div class="collabstoragecounter" id="progress">
                            
                            <div class="collabstoragecurrent progress-bar" style="width:0px;"></div>
                        </div>
                        
                        <div class="collabstoragestats">
                            <span></span>
                            
                            <div class="clear"></div>
                        </div>
                        
                        <div class="clear"></div>
                    </div>
                    <!-- End Progress bar -->
                    
					<?php /* Collab Section (Message Submission): End */ ?>
					
					<?php /* Pagination: Start */ ?>
					<?php /* ********************************************************* */ ?>
					<?php /* NOTE: If only one page of comments, don't show pagination */ ?>
					<?php /* ********************************************************* */ ?>
					
					<?php /* Pagination: End */ ?>
					
					<?php /* Collab Section (Files Output List): Start */ ?>
					<div class="collabsec filesoutput">
						<table class="collabmessagetable collabtable" border="0" id="FILES_TABLE">
                        	<thead>
								<tr>
									<th class="messagesender">
										Files Name
									</th>
									<th class="messageoutput" width="15%">
										Size
									</th>
									<th class="messagedate" width="15%">
										Options
									</th>
								</tr>
							</thead>
                            <?php
							$Alert_Id='';
							if(isset($_GET['Alert_Id'])){
								$Alert_Id=$_GET['Alert_Id'];
							}
							$posts=get_files($params,$Alert_Id);
							?>
							<tfoot>
								<tr>
									<td colspan="3"><span id="file_count"><?php echo sizeof($posts); ?></span> Files</td>
								</tr>
							</tfoot>
							<tbody>
								<?php
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
									<tr class="odd first <?php echo $Extra_Classes; ?>">
										<td class="fileinformation">
											<div class="fileinfofilelink">
												<a href="javascript:void(0);" data-file-id='<?php echo $post->ID; ?>' data-file-name="<?php echo $post->post_title; ?>" onClick="JavaScript:Edd.collaboration.FILES.DOWNLOAD_FILES(this);"><?php echo $post->post_title; ?></a>
											</div>
											<div class="fileinfoadditional">
												Uploaded <?php echo date_format(date_create($post->post_date),'M d, Y - H:i A'); ?> by 
												<a target="_blank" href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $post->post_author; ?>">
												<?php 
													$wp=get_userdata($post->post_author);
													echo $wp->display_name;
												?>
												</a>
											</div>
										</td>
										<td class="filesizetotal">
											<?php 
											$meta=get_post_meta($post->ID,'_wp_collaboration_file_size',true); 
											echo formatSizeUnits($meta);
											?>
										</td>
										<td class="fileslistoptions">
                                        <div class="wrlistitemactionsholder">
											<div class="fileslistoptionstxt wrlistitemactionstxt">
														Options
                                            </div>
											<ul class="fileslistoptionsitems writemactionslist">
												<li class="first"><a href="javascript:void(0);" data-file-id='<?php echo $post->ID; ?>' data-file-name="<?php echo $post->post_title; ?>" onClick="JavaScript:Edd.collaboration.FILES.DOWNLOAD_FILES(this);">Download File</a></li>
												<li class="last"><a  data-file-id='<?php echo $post->ID; ?>' href="javascript:void(0);" onClick="JavaScript:Edd.collaboration.FILES.DELETE_FILES(this);">Delete this file</a></li>
											</ul>
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
        var url ='<?php echo collaboration_plugin_url . 'files/'; ?>';
		
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
                    uploadErrors.push('The following extensions are accepted (<?php echo $ext; ?>) along with a file size 2MB and below.');
                }
				
                var calc=((data.originalFiles[0]['size']/1024)/1024);
                //alert(calc);
				
                if(calc > parseInt('<?php echo get_option('max_upload_size'); ?>')) {
                    uploadErrors.push('File size is too large. The allowed size is <?php echo get_option('max_upload_size'); ?> or smaller.');
                }
				
                if(total>parseInt('<?php echo delete_option('file_limitation_to_upload'); ?>')){
                    uploadErrors.push('You can\'t upload more than <?php echo delete_option('file_limitation_to_upload'); ?> files at a time.');
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
                $.each(data.result.files, function (index, file) {
					var names=file['name'];
					$.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'method':'Save_Files','name':names,'size':file['size'],'Workroom_Id':'<?php echo $params; ?>'},
					}).done(function(msg) {
						Edd.options.FILES_TABLE.fnDestroy()
						$('.dataTables_empty').closest('tr').remove();
						
						var str='<div class="fileinfofilelink"><a href="javascript:void(0)">'+msg.name+'</a></div><div class="fileinfoadditional">Uploaded '+msg.date+' by <a href="javascript:void(0)">'+msg.author+'</a></div>';
						
						
						
						$("#FILES_TABLE>tbody").prepend('<tr>'+
							'<td  class="fileinformation">'+str+'</td>'+
							'<td class="filesizetotal">'+Edd.collaboration.MajerMent.formatSizeUnits(msg.size)+'</td>'+
							'<td  class="fileslistoptions ACTION">'+
								'<div class="wrlistitemactionsholder">'+
									'<div class="fileslistoptionstxt wrlistitemactionstxt">Options</div>'+
									'<ul class="fileslistoptionsitems writemactionslist">'+
									'<li class="first"><a href="javascript:void(0);" data-file-id="'+msg.File_Id+'" data-file-name="'+msg.name+'"  onClick="JavaScript:Edd.collaboration.FILES.DOWNLOAD_FILES(this);">Download File</a></li>'+
									'<li class="first"><a  data-file-id="'+msg.File_Id+'" href="javascript:void(0);" onClick="JavaScript:Edd.collaboration.FILES.DELETE_FILES(this);">Delete this file</a></li>'+
									'</ul>'+
								'</div'+
							'</td>'+
							'</tr>');
							Edd.options.count=parseInt($("#file_count").html());
							$("#file_count").html(Edd.options.count+1);
							Edd.options.FILES_TABLE=$("#FILES_TABLE").dataTable(Edd.options.FILES_GRID);
							
							
							$("#workroom_space_detail").html(msg.Detail.UsedSpaceWithPercentage+" % of Storage Used | "+msg.Detail.UsedSpaceWithUnit+" of "+msg.Detail.TotalSpace+" Used");
							$("#workroom_space_progress").width(msg.Detail.UsedSpaceWithPercentage+'%');
							
					})
				})
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