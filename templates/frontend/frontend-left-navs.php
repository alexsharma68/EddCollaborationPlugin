
<div class="collabcol leftcol nav">
	<div class="collabinside">
		
		<?php /* Collab Section (Collaboration Room Navigation): Start */ 
		
		//add_post_meta('3986','Edd_Parents','yyy');
		//add_post_meta('3986','Edd_Parents','xx');
		//$child_id=get_post_meta('3986','Edd_Parents');

	//if(in_array('xx',$child_id)){
		//echo "Yes";
	//}
	
		?>
		<div class="collabsec wrnav">
			<h2><span>Workroom</span></h2>
			
			<ul class="collabnavlist">
				<li class="odd first <?php echo(UriSegment(1)=='message')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/message')).$params; ?>">
						<span>Messages</span>
					</a>
				</li>
				<li class="even <?php echo(UriSegment(1)=='files')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/files')).$params; ?>">
						<span>Files</span>
					</a>
				</li>
				<li class="odd <?php echo(UriSegment(1)=='team-members')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members')).$params; ?>">
						<span>Team Members</span>
					</a>
				</li>
				<li class="even <?php echo(UriSegment(1)=='team-commission')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-commission')).$params; ?>">
						<span>Commission Proposals</span>
					</a>
				</li>
				<li class="odd <?php echo(UriSegment(1)=='workroom-alert')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/workroom-alerts')).$params; ?>">
						<span>Alerts</span>
					</a>
				</li>
				<li class="even last <?php echo(UriSegment(1)=='report-problem')?'current':''; ?>">
					<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/report-problem')).$params; ?>">
						<span>Report a Problem</span>
					</a>
				</li>
			</ul>
			
			<div class="clear"></div>
		</div>
		<?php /* Collab Section (Collaboration Room Navigation): End */ ?>
		
		<?php /* Collab Section (Collaborators List): Start */ ?>
		<div class="collabsec wrcollaborators">
			<h3>
				<span>Collaborators</span>
				<a href="<?php echo get_permalink(get_page_by_path('collaboration/workroom/team-members-invitation')).$params; ?>" class="addcollabusr">
					+
				</a>
			</h3>
			
			<ul class="collabuserlist">
			<?php
				$collaborators_index=0;
				$rows=get_teams_as_workroom($params);
				foreach($rows as $records){
					$author_id = $records->Collaborator_Id;
					$status=$records->Invitation_Status;
					$main_item_id=$records->Main_Item_Id;
					
					if($status!='Accepted') continue 1;
					$collaborators_index++;
				?>
				<li class="<?php echo $class; ?>">
					<div class="collabusritem">
						<span class="usr">
							<?php echo get_the_author_meta('first_name', $author_id).' '.get_the_author_meta('last_name', $author_id); ?>
						</span>
                        <span class="usrname"><?php echo get_the_author_meta('display_name', $author_id); ?></span>
                        <a href="<?php echo get_permalink(get_page_by_path('user-details')); ?>?id=<?php echo $author_id; ?>" target="_blank" class="collabuserview">
                            <span>View Profile</span>
                        </a>
						<div class="clear"></div>
					</div>
				</li>
				<?php } if($collaborators_index==0){ ?>	
				<li>
					<span>No Collaborators found.</span>
				</li>
				<?php } ?>
			</ul>
			
			<div class="clear"></div>
		</div>
		<?php /* Collab Section (Collaborators List): End */ ?>
		
		<?php /* Collab Section (Recent Files): Start */ ?>
		<div class="collabsec wrrecentfiles">
			<h3><span>Recent Files</span></h3>
			
			<?php /* ********************************************************* */ ?>
			<?php /* NOTE: show only the latest 5, with the most recent at top */ ?>
			<?php /* ********************************************************* */ ?>
			<ul class="collabfileslist" style="overflow-x: hidden;">
			<?php
				$files=get_files($params);
				$fileCount=0;
				
				if(sizeof($files)>0){
					foreach($files as $file){
						if($fileCount>4) break;
						$fileCount++;
						?>
						<li class="odd first">
							<a href="javascript:void(0);" 
								data-file-id='<?php echo $file->ID; ?>' 
								data-file-name="<?php echo $file->post_title; ?>" 
								onClick="JavaScript:Edd.collaboration.FILES.DOWNLOAD_FILES(this);">
								<span class="collabfilename">
									<?php echo $file->post_title; ?>
								</span>
								<span class="collabfiledate">
									<?php echo date_format(date_create($file->post_date),'M d, Y - H:i A'); ?>
								</span>
							</a>
						</li>
						<?php
					}
				}else{
					echo '<li class="odd first">File not Uploaded.</li>';
				}
				?>
			</ul>
			
			<div class="collabfilescounter">
				<div class="collabfilecountertxt">
					Total number of files: <?php echo sizeof($files); ?>
				</div>
				
				<div class="clear"></div>
			</div>
			
			<div class="collabfilescontrols">
				<ul class="collabfilecontrolslist">
					<li class="first">
						<a href="<?php echo UriSegment(1)=='files'?'JavaScript:void(0)':get_permalink(get_page_by_path('collaboration/workroom/files')).$params; ?>" <?php echo (UriSegment(1)=='files')?' onClick="JavaScript:Edd.collaboration.FILES.VIEW_ALL();"':''; ?>>
							View All Files
						</a>
					</li>
					<li class="sep"><span>|</span></li>
					<li class="last">
						<a href="<?php echo UriSegment(1)=='files'?'JavaScript:void(0)':get_permalink(get_page_by_path('collaboration/workroom/files')).$params; ?>" <?php echo (UriSegment(1)=='files')?' onClick="JavaScript:Edd.collaboration.FILES.UPLOAD_NEW_FILE();"':''; ?>>
							Upload New File
						</a>
					</li>
				</ul>
				
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
		<?php /* Collab Section (Recent Files): End */ ?>
		
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>
</div>
<?php /* Left Col Navigation: End */ ?>
