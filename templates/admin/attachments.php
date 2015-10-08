<!-- Uploader Controds -->
                                
                                <link rel="stylesheet" href="<?php echo collaboration_plugin_url.'assets/css/jquery.fileupload.css'; ?>">

<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.ui.widget.js'; ?>"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.iframe-transport.js'; ?>"></script>

<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="<?php echo collaboration_plugin_url.'assets/js/jquery.fileupload.js'; ?>"></script>
                                
                                <!-- End upload Controld -->
<div class="clear">&nbsp;</div>
                                           
                                            <div class="clear"></div>
                                            <div class="collabstoragecounter" id="progress">
										
                                                <div class="collabstoragecurrent progress-bar" style="width:0px;"></div>
                                            </div>
                                            <div class="collabstoragestats">
                                                <span></span>
                                                <div class="clear"></div>
                                            </div>
                                            <div id="files"></div>
                                            <div class="clear"></div>
                                             <a href="JavaScript:void(0)" class="attachment" style="text-decoration:none;" id="ATTACHMENTS"><label for="fileupload" style="margin:0px!important; padding:0px!important;">+ Add attachment</label><input style="display:none;" id="fileupload" type="file" name="files[]" multiple /></a>
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
								uploadErrors.push('we accept (<?php echo $ext;?>) files and files size below 2MB');
							}
							var calc=((data.originalFiles[0]['size']/1024)/1024);
							//alert(calc);
							if(calc > parseInt('<?php echo get_option('max_upload_size'); ?>')) {
								uploadErrors.push('Filesize is too big, allowed size is only 2MB');
							}
							if(total>2){
								uploadErrors.push('You cant upload more than 2 file'); 
							}
							if(uploadErrors.length > 0) {
								alert(uploadErrors.join("\n"));
							} else {
								data.submit();
							}
							
                        },
                        maxNumberOfFiles			: 2,
                        limitMultiFileUploadSize 	: '2050',
                        maxFileSize					: '2000',
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