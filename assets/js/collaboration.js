/*
EED JS Plugin.
Created by Durga Sharma
*/

(function(Edd,jQ){
	var jQ=jQuery.noConflict();
	var Edd=Edd.Edd;
	Edd.options.Vars={};
	Edd.options.PostData={};
	Edd.collaboration={
		init: function() {
			Edd.options.MESSAGE_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : false,
				"bFilter" : false,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top"flp>rt<"bottom"i><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "No message added yet.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			};
			Edd.options.FILES_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : false,
				"bFilter" : false,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top"flp>rt<"bottom"i><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "Files are not found for this workroom.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			};
			Edd.options.MEMBER_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : false,
				"bFilter" : false,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top"flp>rt<"bottom"i><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "Collaborators are not found for this item. Perhaps this item has not been resold yet.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			};
			Edd.options.MEMBER_INVITE_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : false,
				"bFilter" : false,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top"flp>rt<"bottom"i><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "Collaborators are not found for this item. Perhaps this item has not been resold yet.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			};
			Edd.options.WORKROOM_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : true,
				"bFilter" : true,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top">rt<"bottom"lp><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "Workrooms are not found.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			}
			Edd.options.ALERTS_GRID={
				"paging" : true,
				"ordering" : true,
				"info" : false,
				"bFilter" : false,
				"iDisplayLength":10,
				"bSort": false,
				"bStateSave":false,
				"scrollCollapse" : false,
				"sDom": '<"top"flp>rt<"bottom"i><"clear">R',
				"aaSorting": [],
				"oLanguage": {
				"sEmptyTable": "You have 0 Alerts.",
				"sLengthMenu": " _MENU_ "
				},
				"fnDrawCallback": function(e){
					/*Edd.collaboration.TableSettings.CheckLength(e)*/
					Edd.collaboration.TableSettings.SetClasses(e);
				},
				"columnDefs": [{ orderable: false, "targets": -1 }]
			}
			Edd.collaboration.DASHBOARD.INIT_DASHBOARD();
			Edd.collaboration.FILES.INIT_FILES();
			Edd.collaboration.TEAM_MEMBERS.INIT_TEAM_MEMBERS();
			Edd.collaboration.STAGE.INIT();
			Edd.collaboration.Workroom.Init();
			Edd.collaboration.Alerts.Init(); 
		},
		
		FILES:{
			INIT_FILES:function(){
				Edd.options.FILES_TABLE=jQ("#FILES_TABLE").dataTable(Edd.options.FILES_GRID);
			},
			DOWNLOAD_FILES:function(e){
				Edd.options.Vars.File_Name=jQ(e).attr('data-file-name');
				Edd.options.Vars.File_Download_Url=Edd.PLUGIN_URL+"classes/class-collaboration.php?Method=Download_File&File_Name="+Edd.options.Vars.File_Name;
				jQ('<iframe src="' + Edd.options.Vars.File_Download_Url + '" style="display:none;"/>').appendTo("body").on("load",function(n){
					//jQ(this).remove();				
				})
			},
			VIEW_ALL:function(){
				jQ("html, body").animate({ scrollTop: "200px" });
				Edd.options.Vars.Settings=Edd.options.FILES_TABLE.fnSettings();
				Edd.options.Vars.Settings._iDisplayLength=-1;
				Edd.options.FILES_TABLE.fnDraw(Edd.options.Vars.Settings);
			},
			UPLOAD_NEW_FILE:function(){
				jQ("html, body").animate({ scrollTop: "200px" });
			},
			DELETE_FILES:function(e){ 
				Edd.options.Vars.File_Id=jQ(e).attr('data-file-id');
				Edd.options.PostData.File_Id=Edd.options.Vars.File_Id;
				Edd.options.PostData.Method='Delete_File';
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) { 
					if(Edd.options.admin){ 
						window.location.reload(); 
					}else{
						Edd.options.FILES_TABLE.fnDestroy()
						jQ('a[data-file-id="'+msg.File_Id+'"]').closest('tr').remove();
						jQ('.dataTables_empty').closest('tr').remove();
						Edd.options.count=parseInt(jQ("#file_count").html());
						jQ("#file_count").html(Edd.options.count-1);
						Edd.options.FILES_TABLE=jQ("#FILES_TABLE").dataTable(Edd.options.FILES_GRID);
						jQ("#workroom_space_detail").html(msg.Detail.UsedSpaceWithPercentage+" % of Storage Used | "+msg.Detail.UsedSpaceWithUnit+" of "+msg.Detail.TotalSpace+" Used");
						jQ("#workroom_space_progress").width(msg.Detail.UsedSpaceWithPercentage+'%');
					}
				})
			}
		},
		TEAM_MEMBERS:{
			INIT_TEAM_MEMBERS:function(){
				Edd.options.MEMBER_TABLE=jQ("#MEMBER_TABLE").dataTable(Edd.options.MEMBER_GRID);
				Edd.options.MEMBER_INVITE_TABLE=jQ("#MEMBER_INVITE_TABLE").dataTable(Edd.options.MEMBER_INVITE_GRID);
				jQ(document).on("change","#FILTER_BY_ITEM",function(){
				if(jQ(this).val()=='')return false;
					Edd.collaboration.TEAM_MEMBERS.FILTER_TEAM_MEMBERS(jQ(this).val());	
				})
				jQ(document).on("click",".INVITE_TEAM",function(){
					Edd.options.PostData.Method='Invitation_Collaborators';
					Edd.options.PostData.Workroom_Id=jQ(this).attr('data-workroom-id');
					Edd.options.PostData.Collaborator_Id=jQ(this).attr('data-collaborator-id');
					Edd.options.PostData.Action=jQ(this).attr('data-action');
					Edd.collaboration.TEAM_MEMBERS.INVITATION_TEAM_MEMBERS(Edd.options.PostData);
				});
			},
			EDIT_MEMBERS:function(e){
				Edd.options.PostData.Method='Edit_Collaborators';
				Edd.options.PostData.Workroom_Id=jQ(e).attr('data-workroom-id');
				Edd.options.PostData.Collaborator_Id=jQ(e).attr('data-collaborator-id');
				Edd.options.PostData.Action=jQ(e).attr('data-action');
				Edd.options.PostData.Alert_Id=jQ(e).attr('data-alert-id');
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					if(Edd.options.admin){ 
						window.location.reload(); 
					}else{
						Edd.options.MEMBER_TABLE.fnDestroy()
						jQ('a[data-collaborator-id="'+msg.Collaborator_Id+'"]').closest('tr').remove();
						Edd.options.MEMBER_TABLE.dataTable(Edd.options.MEMBER_GRID);
					}
				})	
			},
			FILTER_TEAM_MEMBERS:function(e){
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:{'method':'Update_Collaborators_Table','Workroom_Id':e},
				}).done(function(msg) {
					Edd.options.MEMBER_INVITE_TABLE.fnDestroy()
					jQ("#MEMBER_INVITE_TABLE tbody").empty();
					jQ.each(msg,function(index,data){
					var str='<tr><td class="userinformation">'+
								'<div class="usermainname">'+data.name+'</div>'+
								'<div class="usersusername">'+data.display_name+'</div>'+
							'</td>'+
							'<td class="usersrole">'+
								data.item_name+
							'</td>'+
							'<td class="userslistoptions ACTION" align="right">'+
								'<button type="button" data-workroom-id="'+data.workroom_id+'" data-download-id="'+data.download_id+'" data-author-id="'+data.author_id+'" data-action="Invited" class="INVITE_TEAM">Invite</button>'+
							'</td></tr>';
							jQ("#MEMBER_INVITE_TABLE tbody").append(str);
					})
					Edd.options.MEMBER_INVITE_TABLE.dataTable(Edd.options.MEMBER_INVITE_GRID);
				})
			},
			INVITATION_TEAM_MEMBERS:function(e){
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:e,
				}).done(function(msg) {
					Edd.options.MEMBER_INVITE_TABLE.fnDestroy()
					jQ('[data-collaborator-id="'+msg.Collaborator_Id+'"]').closest('tr').remove();
					Edd.options.MEMBER_INVITE_TABLE.dataTable(Edd.options.MEMBER_INVITE_GRID);
					jQ("#count_collaborators").html(parseInt(jQ("#count_collaborators").html()-1));
				})
			}
		},
		DASHBOARD:{
			INIT_DASHBOARD:function(){
				Edd.options.MESSAGE_TABLE=jQ("#MESSAGE_TABLE").dataTable(Edd.options.MESSAGE_GRID);
				jQ('#CHANGE_STATUS').click(function(){
					jQ.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'method':'Change_Status','Change_To':jQ('#status_to').val(),'Workroom_Id':jQ(this).attr('data-workroom-id')},
					}).done(function(msg){
						
						if(Edd.options.admin){ 
							window.location.reload(); 
						}
					})
				})
				jQ(document).on('click','#POST_MESSAGE_BUTTON',function(){
					
					if(jQ("textarea[name='POST_MESSAGE']").val()=='')return false;
					
					jQ.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'method':'Save_Message','metas':jQ('#MESSAGE_FORMS').serialize()},
					}).done(function(msg) {
						Edd.options.Vars.Attachments='';
						if(Edd.options.admin){ 
							window.location.reload(); 
						}else{
							jQ('.dataTables_empty').closest('tr').remove();
							Edd.options.MESSAGE_TABLE.fnDestroy();
							
							if(jQ.isArray(msg.attachments)) {
								Edd.options.Vars.Attachments='<div class="clear"></div><ul class="attachments">'+
																'<li>'+
																'<a href="JavaScript:void(0)">Attachments</a>'+	
																'<ul class="attached-list">';
																
								jQ.each(msg.attachments,function(ind,v){
									Edd.options.Vars.Attachments+='<li>'+
																	'<a  href="javascript:void(0);" data-file-id="'+msg.File_Id+'" data-file-name="'+v+'" onClick="JavaScript:Edd.collaboration.DASHBOARD.DOWNLOAD_ATTACHMENTS(this);">'+v+'</a>'+
																	'</li>';
								})
								Edd.options.Vars.Attachments+='</ul>'+
																	'</li>'+
																	'</ul>';
								jQ("[name='POST_MESSAGE']").val('');           
								jQ("#files").empty();
								jQ('#progress .progress-bar').css(
									'width',
									0 + '%'
								);
							}
							
							jQ("#MESSAGE_TABLE").prepend('<tr><td class="messagesender"><a href="'+msg.Artist_Url+'" target="_blank">'+msg.name+'</a></td><td class="messageoutput"><div class="messageoutput_inside">'+msg.message+Edd.options.Vars.Attachments+'</div></td><td class="messagedate"><div>'+msg.date+'</div><div>'+msg.time+'</div></td></tr>');
							jQ("[name='POST_MESSAGE']").val('');
							Edd.options.MESSAGE_TABLE.dataTable(Edd.options.MESSAGE_GRID);
						}
					})
				})
				
				jQ(document).on('click','#POST_MESSAGE_REPORT_BUTTON',function(){
					
					if(jQ("textarea[name='POST_MESSAGE']").val()=='')return false;
					
					jQ.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'method':'Save_Report_Message','metas':jQ('#MESSAGE_FORMS').serialize()},
					}).done(function(msg) {
						Edd.options.Vars.Attachments='';
						if(Edd.options.admin){ 
							window.location.reload(); 
						}else{
							jQ('.dataTables_empty').closest('tr').remove();
							Edd.options.MESSAGE_TABLE.fnDestroy();
							
							if(jQ.isArray(msg.attachments)) {
								Edd.options.Vars.Attachments='<div class="clear"></div><ul class="attachments">'+
																'<li>'+
																'<a href="JavaScript:void(0)">Attachments</a>'+	
																'<ul class="attached-list">';
																
								jQ.each(msg.attachments,function(ind,v){
									Edd.options.Vars.Attachments+='<li>'+
																	'<a  href="javascript:void(0);" data-file-id="'+msg.File_Id+'" data-file-name="'+v+'" onClick="JavaScript:Edd.collaboration.DASHBOARD.DOWNLOAD_ATTACHMENTS(this);">'+v+'</a>'+
																	'</li>';
								})
									Edd.options.Vars.Attachments+='</ul>'+
																	'</li>'+
												
																	'</ul>';
								jQ("#POST_MESSAGE").empty();            
								jQ("#files").empty();
								jQ('#progress .progress-bar').css(
									'width',
									0 + '%'
								);
							}
							
							jQ("#MESSAGE_TABLE").prepend('<tr><td class="messagesender">'+msg.name+'</td><td class="messageoutput"><div class="messageoutput_inside">'+msg.message+Edd.options.Vars.Attachments+'</div></td><td class="messagedate"><div>'+msg.date+'</div><div>'+msg.time+'</div></td></tr>');
							Edd.options.MESSAGE_TABLE.dataTable(Edd.options.MESSAGE_GRID);
						}
					})
				})
			},
			DOWNLOAD_ATTACHMENTS:function(e){
				Edd.options.Vars.File_Name=jQ(e).attr('data-file-name');
				Edd.options.Vars.File_Download_Url=Edd.PLUGIN_URL+"classes/class-collaboration.php?Method=Download_Attachments&File_Name="+Edd.options.Vars.File_Name;
				jQ('<iframe src="' + Edd.options.Vars.File_Download_Url + '" style="display:none;"/>').appendTo("body").on("load",function(n){
					
					})
			},
		},
		MajerMent:{
			formatSizeUnits:function(bytes){
				if      (bytes >= 1000000000)	{bytes = (bytes/1000000000).toFixed(2) + ' GB';}
				else if (bytes >= 1000000)    	{bytes = (bytes/1000000).toFixed(2) + ' MB';}
				else if (bytes >= 1000)       	{bytes = (bytes/1000).toFixed(2) + ' KB';}
				else if (bytes >  1)           	{bytes = bytes + ' bytes';}
				else if (bytes == 1)          	{bytes = bytes + ' byte';}
				else                          	{bytes = '';}
				return bytes != '' ? "" + bytes + "":'';;
			}
			
		},
		TableSettings:{
			CheckLength:function(e){
				if (e._iDisplayLength > e.fnRecordsDisplay()) {
					jQ(e.nTableWrapper).find('.top').hide();
				}
			},
			SetClasses:function(e){
				e.oInstance.fnSearchHighlighting();
				jQ('div.dataTables_length select').addClass('select');
			}
		},
		STAGE:{
			INIT:function(){
				Edd.options.Vars.Left_Col_Height=jQ('.collabcol.leftcol').height();
				jQ('.collabcol.collabcontent').css({ 'min-height' : Edd.options.Vars.Left_Col_Height + 25 });
				jQ(window).resize(function(){
					jQ('.collabcol.collabcontent').css({ 'min-height' : Edd.options.Vars.Left_Col_Height + 25 });
				});
				jQ(document).on("mouseenter","td.ACTION",function(){
					jQ(this).find("ul.OPTION_MENUS").show();
				}).on('mouseleave',"td.ACTION",function(){
					jQ(this).find("ul.OPTION_MENUS").hide();
				})
				jQ(document).on("mouseenter","ul.collabuserlist li",function(){
					jQ(this).find("a").show();
				}).on('mouseleave',"ul.collabuserlist li",function(){
					jQ(this).find("a").hide();
				})
				
				/* Workspace Status Dropdown */
				jQ(document).on('click','div.wrstatuslistlabel', function(e){
					e.preventDefault();
					e.stopPropagation();
					
					if(!jQ('.wrlistcontroldropdown').hasClass('active')){
						jQ(this).addClass('active');
						jQ(this).parent().addClass('active');
						jQ(this).parent().find('.wrlistcontroldropdown').addClass('active').slideDown();
					} else {
						jQ(this).removeClass('active');
						jQ(this).parent().removeClass('active');
						jQ(this).parent().find('.wrlistcontroldropdown').removeClass('active').slideUp(25);
					}
				});
				
				/* Workspace Status Dropdown Select */
				jQ(document).on('click','li.wrslistitem', function(e){
					var statusLabel = jQ(this).text();
					Edd.collaboration.Workroom.Get_Filter(jQ(this).data('action'));
					
					if(!jQ(this).hasClass('active')){
						jQ('li.wrslistitem').removeClass('active');
						jQ(this).addClass('active');
						
						/* Assign text value of clicked element */
						jQ('span.wrstatusactivelabel').html(statusLabel);
						
						/* Revert back to defaults upon clicking */
						jQ('div.wrstatuslistlabel, .wrlistcontroldropdown').removeClass('active');
						jQ('.wrlistcontroldropdown').slideUp(25);
					}
				});
				
				/* Close Workspace Status Dropdown on Mouse Leave */
				jQ(document).on('mouseleave','div.wrstatuslist',function(){
					if(jQ(this).hasClass('active')){
						jQ(this).removeClass('active');
						jQ(this).find('div.wrstatuslistlabel').removeClass('active');
						jQ(this).find('ul.wrlistcontroldropdown').slideUp(25).removeClass('active');
					}
				});
				
				/* Actions Dropdown */
				jQ(document).on('click','div.wrlistitemactionstxt', function(e){
					
					if(!jQ(this).hasClass('active')){
						
						/* Restore Default State */
						jQ('div.wrlistitemactionstxt').removeClass('active');
						jQ('ul.writemactionslist').css({display: 'none'});
						
						/* Add 'active' Class to Clicked Item */
						jQ(this).addClass('active');
						jQ(this).parent().addClass('active');
						jQ(this).parent().find('ul.writemactionslist').slideDown(50);
					} else {
						jQ(this).removeClass('active');
						jQ(this).parent().removeClass('active');
						jQ(this).parent().find('ul.writemactionslist').slideUp(25);
					}
				});
				
				/* Close Actions Dropdown on Mouse Leave */
				jQ(document).on('mouseleave','div.wrlistitemactionsholder',function(e){
					e.stopPropagation();
					
					if(jQ(this).hasClass('active')){
						jQ(this).removeClass('active');
						jQ(this).find('div.wrlistitemactionstxt').removeClass('active');
						jQ(this).find('ul.writemactionslist').slideUp(25);
					}
				});
			}
		},
		COMMISSION:{
			Update_Commission:function(e){
				
				Edd.options.PostData.Method='Update_Commission';
				Edd.options.PostData.Workroom_Id=jQ(e).attr('data-workroom-id');
				Edd.options.PostData.Author_Id=jQ(e).attr('data-author-id');
				Edd.options.PostData.Commission_Amount=jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).val();
				jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).removeAttr('style');
				if(jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).val()==''){
					jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).css({'border-color':'#FF0000'});
					return false;
				}
				
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).html(msg.Status_Message);
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).slideDown();
					if(msg.Show==true){
						Edd.collaboration.Show_Approve_Check_Boxes();
					}
					setTimeout(function(){
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).slideUp();
					},5000);
				})
			},
			Approve_User_Commission:function(e){
				Edd.options.Vars.Commission_Approved='NO';
				
				if(jQ(e).is(":checked")){
					Edd.options.Vars.Commission_Approved='YES'
				}
				
				Edd.options.PostData.Method='Approve_User_Commission';
				Edd.options.PostData.Workroom_Id=jQ(e).attr('data-workroom-id');
				Edd.options.PostData.Author_Id=jQ(e).attr('data-author-id');
				Edd.options.PostData.Commission_Amount=jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).val();
				Edd.options.PostData.Commission_Approved=Edd.options.Vars.Commission_Approved;
				
				jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).removeAttr('style');
				if(jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).val()==''){
					jQ("#COMMISSION_"+Edd.options.PostData.Author_Id).css({'border-color':'#FF0000'});
					jQ(e).prop('checked',false);
					return false;
				}
				
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).html(msg.Status_Message);
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).slideDown();
					jQ("input[data-author-id='"+Edd.options.PostData.Author_Id+"']").attr('disabled','disabled');
					jQ("input[data-author-id='"+Edd.options.PostData.Author_Id+"']").prop('disabled', true);
					setTimeout(function(){
					jQ("#MESSAGE-"+Edd.options.PostData.Author_Id).slideUp();
					},5000);
				})
			},
			Approve_Commission:function(e){
				Edd.options.PostData.Method='Approve_Commission';
				Edd.options.PostData.Workroom_Id=jQ(e).attr('data-workroom-id');
				Edd.options.PostData.Author_Id=jQ(e).attr('data-control-author-id');
				
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					Edd.collaboration.SetErrorMessage(msg);
					if(msg.Status=='success'){
						jQ("[data-control-author='"+Edd.options.PostData.Author_Id+"']").prop('disabled',true);
					}
				})
			}
		},
		Alerts:{
			Read:function(e){
				jQ("#Alert_Count").html((parseInt(jQ("#Alert_Count").html())-1));
				jQ("li[data-alert-id='"+e+"']").remove();
				if(jQ("#alert_ul").find("li").length<1){
					jQ("#alert_ul").remove();
				}
			},
			Init:function(){
				Edd.options.ALERTS_TABLE=jQ("#Alert_Grid_List").dataTable(Edd.options.ALERTS_GRID);
				Edd.options.PostData.Method='Delete_Alerts';
				
				jQ(document).on("click","#Delete_Alerts",function(){
					jQ.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'Method':Edd.options.PostData.Method,'metas':jQ("#Alert_List").serialize()},
					}).done(function(msg) {
						Edd.options.ALERTS_TABLE.fnDestroy();
						
						jQ.each(msg,function(Index,Value){
							jQ("tr[data-id='"+Value+"']").remove();
						})
						
						jQ("td #Total_Alerts").html(jQ("#Alert_Grid_List>tbody tr").length);
						Edd.options.ALERTS_TABLE=jQ("#Alert_Grid_List").dataTable(Edd.options.ALERTS_GRID);
					})
				})
			}
		},
		Workroom:{
			Delete:function(e){
				Edd.options.PostData.Method='Delete_Workroom';
				Edd.options.PostData.Workroom_Id=e;
				
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					if(msg.Status=='success'){
					if(Edd.options.admin)window.location=msg.URL;
						Edd.options.WORKROOM_TABLE.fnDestroy();
						jQ("[data-workroom-id='"+msg.Workroom_Id+"']").remove();
						jQ("td #Total_Workroom").html(jQ("#WORKROOM_TABLE>tbody tr").length);
						Edd.options.WORKROOM_TABLE=jQ("#WORKROOM_TABLE").dataTable(Edd.options.WORKROOM_GRID);
					}else{
						Edd.collaboration.SetErrorMessage(msg);
					}
				})
			},
			Init:function(){
				
				Edd.options.WORKROOM_TABLE=jQ("#WORKROOM_TABLE").dataTable(Edd.options.WORKROOM_GRID);
				jQ(document).on('click','#WOEKROOM_SEARCH',function(){ 
					Edd.options.WORKROOM_TABLE.fnFilter(jQ("#EDD_FILTER_WORKROOM").val());
				});
				
				jQ(document).on('click','#ADD_WORKROOM_BUTTON',function(){
					jQ('#workroom_name,#workroom_description,#Unfinished_Item_ID').removeAttr('style')
					if(jQ('#workroom_name').val()==''){
						Edd.collaboration.SetErrorMessage({'Message':'Please fill out workroom Name.','Status':'error'});
						jQ('#workroom_name').css({'border-color':'#FF0000'});
						return false;	
					}
					if(jQ('#workroom_description').val()==''){
						Edd.collaboration.SetErrorMessage({'Message':'Please fill out workroom description.','Status':'error'});
						jQ('#workroom_description').css({'border-color':'#FF0000'})
						return false;
					}
					if(jQ('#Unfinished_Item_ID').val()==null || jQ('#Unfinished_Item_ID').val()==''){
						Edd.collaboration.SetErrorMessage({'Message':'Please select a unfinished item.','Status':'error'});
						jQ('#Unfinished_Item_ID').css({'border-color':'#FF0000'})
						return false;
					}
					jQ.ajax({
						type: "POST",
						url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
						dataType: "json",
						data:{'method':'Update_Workroom','metas':jQ('#WORKROOM_FORM').serialize()},
					}).done(function(msg) {
						Edd.collaboration.SetErrorMessage(msg);
						jQ('#WORKROOM_FORM').find("input,textarea").val('');
						window.location=msg.URL;
					})
				});
			},
			Get_Filter:function(e){
				Edd.options.PostData.Method="Filter_Workroom";
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "text",
					data:{'Method':Edd.options.PostData.Method,'Type':e},
				}).done(function(msg) {
					Edd.options.WORKROOM_TABLE.fnDestroy();
					jQ("#WORKROOM_TABLE>tbody").empty();
					jQ("#WORKROOM_TABLE>tbody").prepend(msg);
					jQ("td #Total_Workroom").html(jQ("#WORKROOM_TABLE>tbody tr").length);
					Edd.options.WORKROOM_TABLE=jQ("#WORKROOM_TABLE").dataTable(Edd.options.WORKROOM_GRID);
				})
			}
		},
		SetErrorMessage:function(e){
			jQ("#GLOBAL_MESSAGE_TOP").html(e.Message);
			jQ("#GLOBAL_MESSAGE_TOP").addClass(e.Status).slideDown();
			jQ("html, body").animate({ scrollTop: "50px" });
			if(e.hasOwnProperty('Fade') && e.Fade==false)return;
			setTimeout(function(){
				jQ("#GLOBAL_MESSAGE_TOP").slideUp().removeClass('error,success');
			},8000);
		},
		Show_Approve_Check_Boxes:function(){
			jQ(".confappwrpr").show();
		},
		Report_Problem:{
			Submit_Problem:function(){
				Edd.options.PostData.Method="Report_Problem";
				Edd.options.PostData.Reason=jQ("#Problem_Reason").val();
				Edd.options.PostData.Title=jQ("#Problem_Title").val();
				Edd.options.PostData.Workroom_Id=jQ("#Workroom_Id").val();
				jQ.ajax({
					type: "POST",
					url: Edd.PLUGIN_URL+"classes/class-collaboration.php",
					dataType: "json",
					data:Edd.options.PostData,
				}).done(function(msg) {
					Edd.collaboration.SetErrorMessage(msg);
					jQ("#Problem_Reason").val('');
					jQ("#Problem_Title").val('');
				})
			}
		}
		
	};
	Edd.collaboration.init();
})(this,jQuery);
